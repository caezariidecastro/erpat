<?php

use ParagonIE\Sodium\Core\Curve25519\Ge\P2;

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Purchase_orders extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Purchase_orders_model");
        $this->load->model("Vendors_model");
        $this->load->model("Material_entries_model");
        $this->load->model("Purchase_order_materials_model");
        $this->load->model("Purchase_order_budgets_model");
        $this->load->model("Material_inventory_model");
        $this->load->model("Accounts_model");
        $this->load->model("Account_transactions_model");
    }

    protected function _get_vendor_dropdown_data() {
        $vendor = $this->Vendors_model->get_all()->result();
        $vendor_dropdown = array('' => '-');

        foreach ($vendor as $vendor) {
            $vendor_dropdown[$vendor->id] = $vendor->name;
        }
        return $vendor_dropdown;
    }

    protected function _get_vendor_select2_data() {
        $vendor = $this->Vendors_model->get_all()->result();
        $vendor_select2 = array(array("id" => "", "text" => "- ".lang("suppliers")." -"));

        foreach ($vendor as $vendor) {
            $vendor_select2[] = array("id" => $vendor->id, "text" => $vendor->name);
        }
        return $vendor_select2;
    }

    protected function _get_accounts_dropdown_data() {
        $accounts = $this->Accounts_model->get_all()->result();
        $accounts_dropdown = array('' => '-');

        foreach ($accounts as $group) {
            $accounts_dropdown[$group->id] = $group->name;
        }
        return $accounts_dropdown;
    }

    function index(){
        $view_data["vendor_select2"] = $this->_get_vendor_select2_data();
        $this->template->rander("purchase_orders/index", $view_data);
    }

    function save() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');

        $purchase_order_data = array(
            "vendor_id" => $this->input->post('vendor_id'),
            "remarks" => $this->input->post('remarks'),
        );

        if(!$id){
            $purchase_order_data["created_on"] = date('Y-m-d H:i:s');
            $purchase_order_data["created_by"] = $this->login_user->id;
        }

        $purchase_order_id = $this->Purchase_orders_model->save($purchase_order_data, $id);
        if ($purchase_order_id) {
            $options = array("id" => $purchase_order_id);
            $purchase_order_info = $this->Purchase_orders_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $purchase_order_info->id, "data" => $this->_make_row($purchase_order_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function list_data(){
        $list_data = $this->Purchase_orders_model->get_details(array(
            "vendor_id" => $this->input->post("vendor_select2_filter")
        ))->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_row($data) {
        $status = get_purchase_order_status_label($data->status);

        return array(
            '<a href="'.base_url("pid/purchases/view/".$data->id).'">Purchase #'.$data->id.'</a>',
            $data->vendor_name,
            number_with_decimal($data->amount),
            nl2br($data->remarks),
            $data->created_on,
            get_team_member_profile_link($data->created_by, $data->creator_name, array("target" => "_blank")),
            $status,
            modal_anchor(get_uri("purchase_orders/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_purchase'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("purchase_orders/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function save_material(){
        $id = $this->input->post('id');
        $purchase_id = $this->input->post('purchase_id');
        $vendor_id = $this->input->post('vendor_id');
        $rate = $this->input->post('rate');
        $quantity = $this->input->post('quantity');

        $material_data = array(
            "title" => $this->input->post('title'),
            "unit_type" => $this->input->post('unit_type'),
            "rate" => $rate,
            "quantity" => $quantity,
            "total" => $rate * $quantity,
            "purchase_id" => $purchase_id,
            "material_id" => $this->input->post('material_id'),
            "material_inventory_id" => $this->input->post('material_inventory_id'),
        );

        $purchase_order_material_id = $this->Purchase_order_materials_model->save($material_data, $id);
        if ($purchase_order_material_id) {
            $options = array("id" => $purchase_order_material_id);
            $purchase_order_material_info = $this->Purchase_order_materials_model->get_details($options)->row();

            echo json_encode(array("success" => true, "id" => $purchase_order_material_info->id, "data" => $this->_material_make_row($purchase_order_material_info, $vendor_id, $purchase_id ), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Purchase_orders_model->get_one($this->input->post('id'));
        $view_data["vendor_dropdown"] = $this->_get_vendor_dropdown_data();

        $this->load->view('purchase_orders/modal_form', $view_data);
    }

    function view($purchase_order_id = 0){
        if ($purchase_order_id) {
            $view_data = get_purchase_order_making_data($purchase_order_id);

            if ($view_data) {
                $this->template->rander("purchase_orders/view", $view_data);
            } else {
                show_404();
            }
        }
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));
        
        $id = $this->input->post('id');
        
        if ($this->Purchase_orders_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    function delete_material() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));
        
        $id = $this->input->post('id');
        
        if ($this->Purchase_order_materials_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    private function _material_make_row($data, $vendor_id, $purchase_id) {
        return array(
            $data->title,
            $data->quantity,
            number_with_decimal($data->rate),
            number_with_decimal($data->total),
            modal_anchor(get_uri("purchase_orders/material_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_purchase'), "data-post-id" => $data->id, "data-post-vendor_id" => $vendor_id, "data-post-purchase_id" => $purchase_id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("purchase_orders/delete_material"), "data-action" => "delete-confirmation"))
        );
    }

    function material_list_data($purchase_id = 0, $vendor_id = 0){
        if($purchase_id && $vendor_id){
            $list_data = $this->Purchase_order_materials_model->get_details(array(
                "purchase_id" => $purchase_id
            ))->result();
            $result = array();
            foreach ($list_data as $data) {
                $result[] = $this->_material_make_row($data, $vendor_id, $purchase_id);
            }
            echo json_encode(array("data" => $result));
        }
    }

    function material_modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $vendor_id = $this->input->post("vendor_id");

        $view_data['material_dropdown'] = array("" => "-") + $this->Material_entries_model->get_dropdown_list(array("name"), "id", array("vendor" => $vendor_id));
        $view_data['model_info'] = $this->Purchase_order_materials_model->get_one($this->input->post('id'));
        $view_data["warehouse_dropdown"] = $this->get_warehouses_select2_data($view_data['model_info']->material_id);
        $view_data["purchase_id"] = $this->input->post("purchase_id");
        $view_data["vendor_id"] = $vendor_id;

        $this->load->view('purchase_orders/material_modal_form', $view_data);
    }

    function get_warehouses_select2_data($material_id = 0){
        $material_id = !$material_id ? $this->input->post("material_id") : $material_id;
        $warehouses = $this->Material_inventory_model->get_details(array("material_id" => $material_id))->result();

        $warehouse_list = array(array("id" => "", "text" => "-"));
        foreach ($warehouses as $value) {
            $warehouse_list[] = array("id" => $value->id, "text" => $value->warehouse_name);
        }

        if($this->input->post("json")){
            echo json_encode($warehouse_list);
        }

        return $warehouse_list;
    }

    function delete_budget() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));
        
        $id = $this->input->post('id');
        
        if ($this->Purchase_order_budgets_model->delete($id)) {
            $this->Account_transactions_model->delete_purchase($id);
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    private function _get_purchase_order_status($purchase_id){
        $purchase_info = $this->Purchase_orders_model->get_one($purchase_id);
        $total_budget = $this->Purchase_order_budgets_model->get_purchase_total_budget($purchase_id);
        $total_material = $this->Purchase_order_materials_model->get_purchase_total_material($purchase_id);
        $status = $purchase_info->status;
        
        if($status != "cancelled"){ // draft|partially_budgeted|completed
            if($total_material > 0 && $total_material > 0){
                if(round($total_material) <= round($total_budget)){
                    $status = "completed";
                }
                
                if(round($total_material) > round($total_budget)){
                    $status = "partially_budgeted";
                }
            }
        }

        return $status;
    }

    private function _budget_make_row($data, $purchase_id) {
        return array(
            get_team_member_profile_link($data->created_by, $data->creator_name, array("target" => "_blank")),
            $data->created_on,
            number_with_decimal($data->amount),
            modal_anchor(get_uri("purchase_orders/budget_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_budget'), "data-post-id" => $data->id, "data-post-purchase_id" => $purchase_id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("purchase_orders/delete_budget"), "data-action" => "delete-confirmation"))
        );
    }

    function budget_list_data($purchase_id = 0){
        if($purchase_id){
            $list_data = $this->Purchase_order_budgets_model->get_details(array(
                "purchase_id" => $purchase_id
            ))->result();
            $result = array();
            foreach ($list_data as $data) {
                $result[] = $this->_budget_make_row($data, $purchase_id);
            }
            echo json_encode(array("data" => $result));
        }
    }

    function budget_modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Purchase_order_budgets_model->get_one($this->input->post('id'));
        $view_data["purchase_id"] = $this->input->post("purchase_id");
        $view_data["account_dropdown"] = $this->_get_accounts_dropdown_data();

        $this->load->view('purchase_orders/budget_modal_form', $view_data);
    }

    function save_budget(){
        $id = $this->input->post('id');
        $purchase_id = $this->input->post('purchase_id');
        $account_id = $this->input->post("account_id");
        $amount = $this->input->post("amount");

        $budget_data = array(
            "amount" => $amount,
            "purchase_id" => $this->input->post("purchase_id"),
            "account_id" => $account_id,
        );

        if(!$id){
            $budget_data["created_by"] = $this->login_user->id;
            $budget_data["created_on"] = get_my_local_time("Y-m-d H:i:s");
        }

        $purchase_order_budget_id = $this->Purchase_order_budgets_model->save($budget_data, $id);

        $this->save_purchase_transaction($account_id, $amount, $id, $purchase_order_budget_id);

        if ($purchase_order_budget_id) {
            $options = array("id" => $purchase_order_budget_id);
            $purchase_order_budget_info = $this->Purchase_order_budgets_model->get_details($options)->row();

            $purchase_info = array(
                "status" => $this->_get_purchase_order_status($purchase_id)
            );

            $this->Purchase_orders_model->save($purchase_info, $purchase_id);

            $purchase_status = get_purchase_order_status_label($purchase_info["status"]);

            echo json_encode(array("success" => true, "id" => $purchase_order_budget_info->id, "data" => $this->_budget_make_row($purchase_order_budget_info, $purchase_id), 'message' => lang('record_saved'), "purchase_status" => $purchase_status));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    private function save_purchase_transaction($account_id, $amount, $id, $saved_id){
        $transaction_data = array(
            'account_id' => $account_id,
            'amount' => $amount,
            'reference' => $saved_id
        );

        if(!$id){// Not yet inserted? Insert!
            $this->Account_transactions_model->add_purchase_order($account_id, $amount, $saved_id); 
        }
        else{
            $this->Account_transactions_model->update_purchase_order($saved_id, $transaction_data); 
        }  
    }
}