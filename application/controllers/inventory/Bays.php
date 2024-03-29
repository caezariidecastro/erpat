<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Bays extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Bays_model");
        $this->load->model("Warehouse_model");
        $this->load->model("Zones_model");
        $this->load->model("Racks_model");

        $this->load->helper("utility");
    }

    protected function _get_status_select2_data() {
        $status_select2 = array(
            array("id" => "", "text"  => "- Status -"),
            array("id" => "active", "text"  => "Active"),
            array("id" => "inactive", "text"  => "Inactive"),
        );

        return $status_select2;
    }

    protected function _get_zone_dropdown_data() {
        $zone = $this->Zones_model->get_all()->result();
        $zone_dropdown = array('' => '-');

        foreach ($zone as $group) {
            $zone_dropdown[$group->id] = get_id_name($group->id, 'Z');
        }
        return $zone_dropdown;
    }

    protected function _get_rack_dropdown_data() {
        $rack = $this->Racks_model->get_all()->result();
        $rack_dropdown = array('' => '-');

        foreach ($rack as $group) {
            $rack_dropdown[$group->id] = get_id_name($group->id, 'R');
        }
        return $rack_dropdown;
    }

    protected function _get_warehouse_select2_data() {
        $warehouses = $this->Warehouse_model->get_all()->result();
        $warehouse_select2 = array(array('id' => '', 'text'  => '- Warehouse -'));

        foreach ($warehouses as $group) {
            $warehouse_select2[] = array('id' => $group->id, 'text' => $group->name) ;
        }
        return $warehouse_select2;
    }

    protected function _get_zone_select2_data() {
        $zones = $this->Zones_model->get_all()->result();
        $zone_select2 = array(array('id' => '', 'text'  => '- Zones -'));

        foreach ($zones as $group) {
            $zone_select2[] = array('id' => $group->id, 'text' => get_id_name($group->id, 'Z')) ;
        }
        return $zone_select2;
    }

    protected function _get_rack_select2_data() {
        $racks = $this->Zones_model->get_all()->result();
        $rack_select2 = array(array('id' => '', 'text'  => '- Racks -'));

        foreach ($racks as $group) {
            $rack_select2[] = array('id' => $group->id, 'text' => get_id_name($group->id, 'R')) ;
        }
        return $rack_select2;
    }

    protected function make_labels_dropdown($type = "", $label_ids = "", $is_filter = false) {
        if (!$type) {
            show_404();
        }

        $labels_dropdown = $is_filter ? array(array("id" => "", "text" => "- " . lang("label") . " -")) : array();

        $options = array(
            "context" => $type
        );

        if ($label_ids) {
            $add_label_option = true;

            //check if any string is exists, 
            //if so, not include this parameter
            $explode_ids = explode(',', $label_ids);
            foreach ($explode_ids as $label_id) {
                if (!is_int($label_id)) {
                    $add_label_option = false;
                    break;
                }
            }

            if ($add_label_option) {
                $options["label_ids"] = $label_ids; //to edit labels where have access of others
            }
        }

        $labels = $this->Labels_model->get_details($options)->result();
        foreach ($labels as $label) {
            $labels_dropdown[] = array("id" => $label->id, "text" => $label->title);
        }

        return $labels_dropdown;
    }

    function index($rack_id = 0) {
        //$view_data['warehouse_select2'] = $this->_get_warehouse_select2_data();
        //$view_data['zone_select2'] = $this->_get_zone_select2_data();
        //$view_data['rack_select2'] = $this->_get_rack_select2_data();
        $view_data['status_select2'] = $this->_get_status_select2_data();
        $view_data['bays_labels_dropdown'] = json_encode($this->make_labels_dropdown("bays", "", true));
        $view_data['rack_id'] = $rack_id;
        $this->load->view("warehouse/bays/index", $view_data);
    }

    function list_data($rack_id = 0) {
        $list_data = $this->Bays_model->get_details(array(
            //'warehouse_id' => $this->input->post('warehouse_select2_filter'),
            //'zone_id' => $this->input->post('zone_select2_filter'),
            //'rack_id' => $this->input->post('rack_select2_filter'),
            'rack_id' => $rack_id,
            'status' => $this->input->post('status_select2_filter'),
            'label_id' => $this->input->post('labels_select2_filter'),
        ))->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_row($data) {

        $labels = "";
        if ($data->labels_list) {
            $labels = make_labels_view_data($data->labels_list, true);
        }

        return array(
            get_id_name($data->id, 'B'),
            $data->warehouse_name,
            get_id_name($data->zone_id, 'Z'),
            get_id_name($data->rack_id, 'R'),
            $data->qrcode,
            $data->barcode,
            $data->rfid,
            $labels,
            $data->remarks,
            make_status_view_data($data->status=="active"),
            $data->timestamp,
            get_team_member_profile_link($data->creator_id, $data->created_by),
            modal_anchor(get_uri("inventory/Bays/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_bay'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("inventory/Bays/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function save() {
        validate_submitted_data(array(
            "rack_id" => "numeric"
        ));

        $id = $this->input->post('id');

        $data = array(
            "rack_id" => $this->input->post('rack_id'),
            "qrcode" => $this->input->post('qrcode'),
            "barcode" => $this->input->post('barcode'),
            "rfid" => $this->input->post('rfid'),
            "labels" => $this->input->post('labels') ? $this->input->post('labels') : "",
            "remarks" => $this->input->post('remarks'),
            "status" => $this->input->post('status'),
        );

        if(!$id){
            $data["timestamp"] = get_current_utc_time();
            $data["created_by"] = $this->login_user->id;
        }
        
        $saved_id = $this->Bays_model->save($data, $id);
        if ($saved_id) {
            $options = array("id" => $saved_id);
            $model_info = $this->Bays_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $model_info->id, "data" => $this->_make_row($model_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Bays_model->get_one($this->input->post('id'));
        $view_data['rack_dropdown'] = $this->_get_rack_dropdown_data();
        $view_data['label_suggestions'] = $this->make_labels_dropdown("bays", $view_data['model_info']->labels);

        $view_data['status_select2'] = $this->_get_status_select2_data();

        $this->load->view('warehouse/bays/modal_form', $view_data);
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');

        if ($this->Bays_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }
}
