<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Purchase_order_returns extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Purchase_order_returns_model");
        $this->load->model("Purchase_orders_model");
        $this->load->model("Vendors_model");
        $this->load->model("Purchase_order_materials_model");
        $this->load->model("Purchase_order_return_materials_model");
        $this->load->model("Purchase_order_budgets_model");
        $this->load->model("Account_transactions_model");
    }

    protected function _get_vendor_select2_data() {
        $vendor = $this->Vendors_model->get_all()->result();
        $vendor_select2 = array(array("id" => "", "text" => "- ".lang("suppliers")." -"));

        foreach ($vendor as $vendor) {
            $vendor_select2[] = array("id" => $vendor->id, "text" => $vendor->name);
        }
        return $vendor_select2;
    }

    protected function _get_purchase_dropdown_data() {
        $purchase = $this->Purchase_orders_model->get_details(array("status" => "completed"))->result();
        $purchase_dropdown = array("" => " - ");

        foreach ($purchase as $purchase) {
            $purchase_dropdown[$purchase->id] = lang("purchase")." #".$purchase->id;
        }
        return $purchase_dropdown;
    }

    protected function _get_purchase_materials_dropdown_data($purchase_id) {
        $materials = $this->Purchase_order_materials_model->get_purchase_materials($purchase_id)->result();
        $materials_dropdown = array("" => "-");

        foreach ($materials as $material) {
            $materials_dropdown[$material->id] = $material->title." (".lang("purchased").": ".$material->quantity.")";
        }
        return $materials_dropdown;
    }

    function index(){
        $view_data["vendor_select2"] = $this->_get_vendor_select2_data();
        $this->template->rander("purchase_order_returns/index", $view_data);
    }

    function list_data(){
        $list_data = $this->Purchase_order_returns_model->get_details(array(
            'vendor_id' => $this->input->post('vendor_select2_filter'),
        ))->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_row($data) {
        $delete = '<li role="presentation">' . js_anchor("<i class='fa fa-times fa-fw'></i>" . lang('delete'), array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("purchase_order_returns/delete"), "data-action" => "delete-confirmation")) . '</li>';
        $add = '<li role="presentation">' . modal_anchor(get_uri("purchase_order_returns/add_material_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_view_material'), array( "title" => lang('add_view_material'), "data-post-purchase_id" => $data->purchase_id, "data-post-id" => $data->id)) . '</li>';

        $actions = '<span class="dropdown inline-block" style="position: relative; right: 0; margin-top: 0;">
                        <button class="btn btn-default dropdown-toggle  mt0 mb0" type="button" data-toggle="dropdown" aria-expanded="true">
                            <i class="fa fa-cogs"></i>&nbsp;
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu pull-right" role="menu">' . $add . $delete . '</ul>
                    </span>';

        return array(
            '<a href="'.base_url("pid/purchases/view/".$data->purchase_id).'">'.lang("purchase").' #'.$data->purchase_id.'</a>',
            $data->vendor_name,
            number_with_decimal($data->amount),
            nl2br($data->remarks),
            $data->created_on,
            get_team_member_profile_link($data->created_by, $data->creator_name, array("target" => "_blank")),
            $actions
        );
    }

    function save() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');
        $purchase_id = $this->input->post('purchase_id');

        $return_data = array(
            "purchase_id" => $purchase_id,
            "remarks" => $this->input->post('remarks'),
            "created_on" => get_my_local_time(),
            "created_by" => $this->login_user->id,
        );

        if(!$this->Purchase_order_returns_model->is_purchase_has_return($purchase_id)){
            $return_id = $this->Purchase_order_returns_model->save($return_data, $id);
            if ($return_id) {
                $options = array("id" => $return_id);
                $return_info = $this->Purchase_order_returns_model->get_details($options)->row();
                echo json_encode(array("success" => true, "id" => $return_info->id, "data" => $this->_make_row($return_info), 'message' => lang('record_saved')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
            }
        }
        else{
            echo json_encode(array("success" => false, 'message' => lang("purchase")." #".$purchase_id.lang('has_return_error')));
        }
    }

    function add_material_modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $purchase_id = $this->input->post("purchase_id");

        $view_data['id'] = $this->input->post('id');
        $view_data["purchase_order_materials_dropdown"] = $this->_get_purchase_materials_dropdown_data($purchase_id);
        $view_data["purchase_id"] = $purchase_id;

        $this->load->view('purchase_order_returns/add_material_modal_form', $view_data);
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Purchase_order_returns_model->get_one($this->input->post('id'));
        $view_data["purchases_dropdown"] = $this->_get_purchase_dropdown_data();

        $this->load->view('purchase_order_returns/modal_form', $view_data);
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));
        
        $id = $this->input->post('id');
        
        if ($this->Purchase_order_returns_model->delete($id)) {
            $returned_materials = $this->Purchase_order_returns_model->get_purchase_return_materials($id)->result();
            foreach ($returned_materials as $returned_material) {
                $this->Account_transactions_model->delete_purchase_return($returned_material->id);
                $this->Purchase_order_return_materials_model->delete($returned_material->id);
            }
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    private function _make_material_row($data) {
        return array(
            $data->material_name,
            number_with_decimal($data->quantity),
            nl2br($data->remarks),
            js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("purchase_order_returns/delete_material"), "data-action" => "delete-confirmation"))
        );
    }

    function material_list_data(){
        $purchase_order_return_id = $this->input->get("purchase_order_return_id");
        $result = array();

        if($purchase_order_return_id){
            $list_data = $this->Purchase_order_returns_model->get_purchase_return_materials($purchase_order_return_id)->result();
            foreach ($list_data as $data) {
                $result[] = $this->_make_material_row($data);
            }
        }

        echo json_encode(array("data" => $result));
    }

    function save_material() {
        $purchase_id = $this->input->post('purchase_id');
        $purchase_order_return_id = $this->input->post('purchase_order_return_id');
        $purchase_order_material_id = $this->input->post('purchase_order_material_id');
        $quantity = $this->input->post('quantity');

        $return_data = array(
            "purchase_order_return_id" => $purchase_order_return_id,
            "purchase_order_material_id" => $purchase_order_material_id,
            "quantity" => $quantity,
            "remarks" => $this->input->post('remarks'),
            "created_on" => get_my_local_time(),
            "created_by" => $this->login_user->id,
        );

        if(!$this->Purchase_order_return_materials_model->is_material_has_return($purchase_order_material_id)){
            $return_id = $this->Purchase_order_return_materials_model->save($return_data);

            $amount = $this->Purchase_order_materials_model->get_one($purchase_order_material_id)->rate * $quantity;
            $account_id = $this->Purchase_orders_model->get_details(array("purchase_id" => $purchase_id))->row()->account_id;

            $this->Account_transactions_model->add_purchase_return($account_id, $amount, $return_id);

            if ($return_id) {
                $options = array("id" => $return_id);
                $return_info = $this->Purchase_order_returns_model->get_purchase_return_materials($purchase_order_return_id, $options)->row();
                echo json_encode(array("success" => true, "id" => $return_info->id, "data" => $this->_make_material_row($return_info), 'message' => lang('record_saved')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
            }
        }
        else{
            echo json_encode(array("success" => false, 'message' => lang('material_has_return_error')));
        }
    }

    function delete_material() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));
        
        $id = $this->input->post('id');
        
        if ($this->Purchase_order_return_materials_model->delete($id)) {
            $this->Account_transactions_model->delete_purchase_return($id);
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }
}
