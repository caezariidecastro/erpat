<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Roles extends MY_Controller {

    //Standard list of all permission of a user.
    protected $permission_lists = array();

    function __construct() {
        parent::__construct();
        $this->access_only_admin();
        $this->load->model("Ticket_types_model");

        $this->permission_lists = [ //empty, all, specific => team: 1, member: 23
            //TODO: New and organize
            array("payroll_enable", "Coming Soon", "Payroll", "Enabled", null),
            array("payroll_view", "Coming Soon", "Payroll", "View", null),
            array("payroll_add", "Coming Soon", "Payroll", "Add", null),
            array("payroll_edit", "Coming Soon", "Payroll", "Edit", null),
            array("payroll_remove", "Coming Soon", "Payroll","Remove", null),

            //Project Planning

            //TODO: Soon to be remove
            array("module_hrs", "Coming Soon", "Human Resource", "Enabled", null),
            array("module_mcs", "Coming Soon", "Marketing", "Enabled", null),
            array("module_sms", "Coming Soon", "Sales", "Enabled", null),
            array("module_fas", "Coming Soon", "Accounting", "Enabled", null),
            array("module_css", "Coming Soon", "Help Center", "Enabled", null),
            array("module_pms", "Coming Soon", "Project Planning", "Enabled", null),
            array("module_ams", "Coming Soon", "Manufacturing", "Enabled", null),
            array("module_lds", "Coming Soon", "Warehouse & Logistics", "Enabled", null),
            array("module_mes", "Coming Soon", "Manufacturing", "Enabled", null),

            array("hrs_employee_view", "Coming Soon", "HR Employee", "View", null),
            array("hrs_employee_invite", "Coming Soon", "HR Employee", "Invite", null),
            array("hrs_employee_add", "Coming Soon", "HR Employee", "Add", null),
            array("hrs_employee_edit", "Coming Soon", "HR Employee", "Edit", null),
            array("hrs_employee_delete", "Coming Soon", "HR Employee", "Delete", null),

            //Important Access
            array("can_use_biometric", "Coming Soon", "Syntry Guard", "Enabled", null),
            array("can_use_payhp", "Coming Soon", "PayHP", "Enabled", null),

            //Default Permission
            array("estimate", "Coming Soon", "Accounting", "Estimates", null),
            array("expense", "Coming Soon", "Accounting", "Expenses", null),
            array("payroll", "Coming Soon", "Accounting", "Payrolls", null),

            array("announcement", "Coming Soon", "General", "Manage Advisories", null),
            array("disable_event_sharing", "Coming Soon", "General", "Disable Event Sharing", null),

            array("client", "Coming Soon", "Marketing", "Clients", null),
            array("lead", "Coming Soon", "Marketing", "Leads", null),

            array("help_and_knowledge_base", "Coming Soon", "Help Center", "Enabled", null),

            array("can_manage_all_projects", "Coming Soon", "Planning", "Manage All Projects", null),
            array("can_create_projects", "Coming Soon", "Planning", "Create Project", null),
            array("can_edit_projects", "Coming Soon", "Planning", "Edit Projects", null),
            array("can_delete_projects", "Coming Soon", "Planning", "Delete Projects", null),
            array("can_add_remove_project_members", "Coming Soon", "Manage Project Members", "Enabled", null),
            array("can_create_tasks", "Coming Soon", "Planning", "Create Task", null),
            array("can_edit_tasks", "Coming Soon", "Planning", "Edit Task", null),
            array("can_delete_tasks", "Coming Soon", "Planning", "Delete Task", null),
            array("can_comment_on_tasks", "Coming Soon", "Planning", "Can Comment on Task", null),
            array("show_assigned_tasks_only", "Coming Soon", "Planning", "View His/Her Task Only", null),
            array("can_update_only_assigned_tasks_status", "Coming Soon", "Edit His/Her Task Only", "Enabled", null),
            array("can_create_milestones", "Coming Soon", "Planning", "Create Milestone", null),
            array("can_edit_milestones", "Coming Soon", "Planning", "Edit Milestone", null),
            array("can_delete_milestones", "Coming Soon", "Planning", "Delete Milestone", null),
            array("can_delete_files", "Coming Soon", "Planning", "Delete Files", null),

            array("can_view_team_members_contact_info", "Coming Soon", "HR Employee", "View Contacts", null),
            array("can_view_team_members_social_links", "Coming Soon", "HR Employee", "View Social Links", null),
            array("can_delete_leave_application", "Coming Soon", "HR Employee", "Delete Leave Application", null),
            array("hide_team_members_list", "Coming Soon", "HR Employee", "Hide Team Member List", null),

            array("ticket", "Coming Soon", "Default: Ticket", "Access", "dropdown"),
            array("message_permission", "Coming Soon", "Default: Message", "Access", "dropdown"),
            array("leave", "Coming Soon", "Default: Leave", "Access", "dropdown"),
            array("attendance", "Coming Soon", "Default: Attendance", "Access", "dropdown"),
            array("team_member_update_permission", "Coming Soon", "Default: User", "Access", "dropdown"),
            array("timesheet_manage_permission", "Coming Soon", "Default: Timesheet", "Access", "dropdown"),

            array("can_update_account", "New!", "HR Employee", "Update account and password.", null),
            array("can_update_contribution", "New!", "Payroll", "Able to update contributions.", null),
        ];
    }

    //load the role view
    function index() {
        $this->template->rander("roles/index");
    }

    //load the role add/edit modal
    function modal_form() {

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Roles_model->get_one($this->input->post('id'));
        $view_data['roles_dropdown'] = array("" => "-") + $this->Roles_model->get_dropdown_list(array("title"), "id");
        $this->load->view('roles/modal_form', $view_data);
    }

    //save a role
    function save() {
        validate_submitted_data(array(
            "id" => "numeric",
            "title" => "required"
        ));

        $id = $this->input->post('id');
        $copy_settings = $this->input->post('copy_settings');
        $permit = array(
            "title" => $this->input->post('title'),
        );

        if ($copy_settings) {
            $role = $this->Roles_model->get_one($copy_settings);
            $permit["permissions"] = $role->permissions;
        }

        $save_id = $this->Roles_model->save($permit, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    //delete or undo a role
    function delete() {
        validate_submitted_data(array(
            "id" => "numeric|required"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Roles_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Roles_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    //get role list data
    function list_data() {
        $list_data = $this->Roles_model->get_details()->result();
        $result = array();
        foreach ($list_data as $permit) {
            $result[] = $this->_make_row($permit);
        }
        echo json_encode(array("data" => $result));
    }

    //get a row of role list
    private function _row_data($id) {
        $options = array("id" => $id);
        $permit = $this->Roles_model->get_details($options)->row();
        return $this->_make_row($permit);
    }

    //make a row of role list table
    private function _make_row($permit) {
        return array("<a href='#' data-id='$permit->id' class='role-row link'>" . $permit->title . "</a>",
            "<a class='edit'><i class='fa fa-check' ></i></a>" . modal_anchor(get_uri("roles/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "Coming Soon", "title" => lang('edit_role'), "data-post-id" => $permit->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_role'), "class" => "delete", "data-id" => $permit->id, "data-action-url" => get_uri("roles/delete"), "data-action" => "delete"))
        );
    }

    //get permisissions of a role
    function permissions($role_id) {
        if ($role_id) {
            $view_data['model_info'] = $this->Roles_model->get_one($role_id);

            $view_data['members_and_teams_dropdown'] = json_encode(get_team_members_and_teams_select2_data_list());
            $ticket_types_dropdown = array();
            $ticket_types = $this->Ticket_types_model->get_all_where(array("deleted" => 0))->result();
            foreach ($ticket_types as $type) {
                $ticket_types_dropdown[] = array("id" => $type->id, "text" => $type->title);
            }
            $view_data['ticket_types_dropdown'] = json_encode($ticket_types_dropdown);

            $this->load->view("roles/permissions", $view_data);
        }
    }

    public function permission_lists($role_id) {
        $role = $this->Roles_model->get_one($role_id);
        $permissions = unserialize($role->permissions);
        if (!$permissions) {
            $permissions = array();
        }        

        $result = array();
        foreach ($this->permission_lists as $permit) {
            $current = get_array_value($permissions, $permit[0]);

            $option = '<select id="'.$permit[0].'" name="'.$permit[0].'" class="select-permission">
                        <option value="" '.($current?'':'selected="selected"').'>
                            Disable
                        </option>
                        <option value="1" '.($current?'selected="selected"':'').'>
                            Enable
                        </option>
                    </select>';

            if( $permit[4] == "dropdown" ) { 
                $option =  '<select id="'.$permit[0].'" name="'.$permit[0].'" class="toggle_specific select-permission" style="margin-bottom: 5px;">
                                <option value="no" '.($current == "no"?'selected="selected"':'').'>
                                    Disable
                                </option>
                                <option value="all" '.($current == "all"?'selected="selected"':'').'>
                                    All Members
                                </option>
                                <option value="specific" '.($current == "specific"?'selected="selected"':'').'>
                                    Specific
                                </option>
                            </select>'.
                            '<div class="specific_dropdown">
                                <input type="text" value="'.get_array_value($permissions, $permit[0]."_specific").'" name="'.$permit[0].'_specific" id="'.$permit[0].'_specific_dropdown" class="w100p "  data-rule-required="true" data-msg-required="'.lang('field_required').'" placeholder="Choose multiple options."  />    
                            </div>';
            }

            $changes = '<input id="'.$permit[0].'_changes" value="-" style="border: none; background-color: transparent !important; text-align: center; width: -webkit-fill-available;" disabled/>';
                        
            $result[] = array(
                $permit[2],
                $permit[3].' <span class="help" data-toggle="tooltip" title="'.$permit[1].'"><i class="fa fa-question-circle"></i></span>',
                $option,
                $changes,
            );
        }
        echo json_encode(array("data" => $result));
    }

    //save permissions of a role
    function save_permissions() {
        validate_submitted_data(array(
            "id" => "numeric|required"
        ));

        $id = $this->input->post('id');
        $permissions = array();

        foreach( $this->permission_lists as $permit ) {

            $permit_key = $permit[0];
            $permissions[$permit_key] = $this->input->post( $permit_key ); 
            
            if( isset($permit[4]) && $permit[4] == "dropdown" ) { 

                $specific_key = $permit_key.'_specific';
                $specific_val = $this->input->post( $specific_key ); 

                $permissions[$specific_key] = $specific_val; 
                if( $permissions[$permit_key] != 'specific' ) {
                    $permissions[$specific_key] = ''; 
                }
            }
        }

        $permit = array(
            "permissions" => serialize($permissions),
        );

        $save_id = $this->Roles_model->save($permit, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }
}

/* End of file roles.php */
/* Location: ./application/controllers/roles.php */