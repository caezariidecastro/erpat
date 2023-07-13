<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Taxes extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->with_permission('compensation_tax_table', "redirect");
        
        $this->load->model("Taxes_model");
    }

    function index() {
        $this->template->rander("taxes/index");
    }

    function modal_form() {

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Taxes_model->get_one($this->input->post('id'));
        $this->load->view('taxes/modal_form', $view_data);
    }

    function table_modal_form() {

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Taxes_model->get_one($this->input->post('id'));
        $this->load->view('taxes/table_modal_form', $view_data);
    }

    function list_daily() {
        $result = get_compensation_tax('daily');

        $current = [];
        foreach($result as $item) {
            $current[] = array(
                $item[0], "Level ".$item[0], 
                cell_input("daily_tax_level_".$item[0]."_starts_at", $item[1], "number"), 
                cell_input("daily_tax_level_".$item[0]."_not_over", $item[2], "number"), 
                cell_input("daily_tax_level_".$item[0]."_amount", $item[3], "number"), 
                cell_input("daily_tax_level_".$item[0]."_rate", $item[4], "number"), 
            );
        }
        echo json_encode(array("data" => $current));
    }

    function save_daily_tax() {

        $daily_tax_table = array();
        for($i=1; $i<=6; $i++) {
            $daily_tax_table[] = array(
                $i,
                $this->input->post('daily_tax_level_'.$i.'_starts_at'),
                $this->input->post('daily_tax_level_'.$i.'_not_over'),
                $this->input->post('daily_tax_level_'.$i.'_amount'),
                $this->input->post('daily_tax_level_'.$i.'_rate')
            );
        }

        $save_id = $this->Settings_model->save_setting("daily_tax_table", serialize($daily_tax_table), "payroll");
        if ($save_id) {
            echo json_encode(array("success" => true, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function restore_daily_tax() {

        $daily_tax_table = $this->Taxes_model->get_daily_raw_default();

        $save_id = $this->Settings_model->save_setting("daily_tax_table", serialize($daily_tax_table), "payroll");
        if ($save_id) {
            echo json_encode(array("success" => true, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function list_weekly() {
        $result = get_compensation_tax('weekly');

        $current = [];
        foreach($result as $item) {
            $current[] = array(
                $item[0], "Level ".$item[0], 
                cell_input("weekly_tax_level_".$item[0]."_starts_at", $item[1], "number"), 
                cell_input("weekly_tax_level_".$item[0]."_not_over", $item[2], "number"), 
                cell_input("weekly_tax_level_".$item[0]."_amount", $item[3], "number"), 
                cell_input("weekly_tax_level_".$item[0]."_rate", $item[4], "number"), 
            );
        }
        echo json_encode(array("data" => $current));
    }

    function save_weekly_tax() {

        $weekly_tax_table = array();
        for($i=1; $i<=6; $i++) {
            $weekly_tax_table[] = array(
                $i,
                $this->input->post('weekly_tax_level_'.$i.'_starts_at'),
                $this->input->post('weekly_tax_level_'.$i.'_not_over'),
                $this->input->post('weekly_tax_level_'.$i.'_amount'),
                $this->input->post('weekly_tax_level_'.$i.'_rate')
            );
        }

        $save_id = $this->Settings_model->save_setting("weekly_tax_table", serialize($weekly_tax_table), "payroll");
        if ($save_id) {
            echo json_encode(array("success" => true, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function restore_weekly_tax() {

        $weekly_tax_table = $this->Taxes_model->get_weekly_raw_default();

        $save_id = $this->Settings_model->save_setting("weekly_tax_table", serialize($weekly_tax_table), "payroll");
        if ($save_id) {
            echo json_encode(array("success" => true, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function list_biweekly() {
        $result = get_compensation_tax('biweekly');

        $current = [];
        foreach($result as $item) {
            $current[] = array(
                $item[0], "Level ".$item[0], 
                cell_input("biweekly_tax_level_".$item[0]."_starts_at", $item[1], "number"), 
                cell_input("biweekly_tax_level_".$item[0]."_not_over", $item[2], "number"), 
                cell_input("biweekly_tax_level_".$item[0]."_amount", $item[3], "number"), 
                cell_input("biweekly_tax_level_".$item[0]."_rate", $item[4], "number"), 
            );
        }
        echo json_encode(array("data" => $current));
    }

    function save_biweekly_tax() {

        $biweekly_tax_table = array();
        for($i=1; $i<=6; $i++) {
            $biweekly_tax_table[] = array(
                $i,
                $this->input->post('biweekly_tax_level_'.$i.'_starts_at'),
                $this->input->post('biweekly_tax_level_'.$i.'_not_over'),
                $this->input->post('biweekly_tax_level_'.$i.'_amount'),
                $this->input->post('biweekly_tax_level_'.$i.'_rate')
            );
        }

        $save_id = $this->Settings_model->save_setting("biweekly_tax_table", serialize($biweekly_tax_table), "payroll");
        if ($save_id) {
            echo json_encode(array("success" => true, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function restore_biweekly_tax() {

        $biweekly_tax_table = $this->Taxes_model->get_biweekly_raw_default();

        $save_id = $this->Settings_model->save_setting("biweekly_tax_table", serialize($biweekly_tax_table), "payroll");
        if ($save_id) {
            echo json_encode(array("success" => true, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function list_monthly() {
        $result = get_compensation_tax('monthly');

        $current = [];
        foreach($result as $item) {
            $current[] = array(
                $item[0], "Level ".$item[0], 
                cell_input("monthly_tax_level_".$item[0]."_starts_at", $item[1], "number"), 
                cell_input("monthly_tax_level_".$item[0]."_not_over", $item[2], "number"), 
                cell_input("monthly_tax_level_".$item[0]."_amount", $item[3], "number"), 
                cell_input("monthly_tax_level_".$item[0]."_rate", $item[4], "number"), 
            );
        }
        echo json_encode(array("data" => $current));
    }

    function save_monthly_tax() {

        $monthly_tax_table = array();
        for($i=1; $i<=6; $i++) {
            $monthly_tax_table[] = array(
                $i,
                $this->input->post('monthly_tax_level_'.$i.'_starts_at'),
                $this->input->post('monthly_tax_level_'.$i.'_not_over'),
                $this->input->post('monthly_tax_level_'.$i.'_amount'),
                $this->input->post('monthly_tax_level_'.$i.'_rate')
            );
        }

        $save_id = $this->Settings_model->save_setting("monthly_tax_table", serialize($monthly_tax_table), "payroll");
        if ($save_id) {
            echo json_encode(array("success" => true, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function restore_monthly_tax() {

        $monthly_tax_table = $this->Taxes_model->get_monthly_raw_default();

        $save_id = $this->Settings_model->save_setting("monthly_tax_table", serialize($monthly_tax_table), "payroll");
        if ($save_id) {
            echo json_encode(array("success" => true, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function save() {

        validate_submitted_data(array(
            "id" => "numeric",
            "title" => "required",
            "percentage" => "required"
        ));

        $id = $this->input->post('id');
        $data = array(
            "title" => $this->input->post('title'),
            "percentage" => unformat_currency($this->input->post('percentage'))
        );
        $save_id = $this->Taxes_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "numeric|required"
        ));


        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Taxes_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Taxes_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    function list_data() {
        $list_data = $this->Taxes_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Taxes_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    private function _make_row($data) {
        return array($data->title,
            to_decimal_format($data->percentage),
            modal_anchor(get_uri("taxes/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_tax'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_tax'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("taxes/delete"), "data-action" => "delete"))
        );
    }

}

/* End of file taxes.php */
/* Location: ./application/controllers/taxes.php */