<?php

class Users_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'users';
        parent::__construct($this->table);
    }

    function set_meta($user_id, $meta_key, $meta_val) {
        $users_meta_table = $this->db->dbprefix('users_meta');

        $where = array(
            'user_id' => $user_id, 
            'meta_key' => $meta_key
        );

        $data = array(
            "meta_val" => $meta_val
        );

        $exists = $this->db->get_where($users_meta_table, $where);
        if ($exists->num_rows()) {
            $this->db->where('user_id', $user_id);
            $this->db->where('meta_key', $meta_key);
            return $this->db->update( $users_meta_table, $data );
        } else {
            $data['user_id'] = $user_id;
            $data['meta_key'] = $meta_key;
            return $this->db->insert( $users_meta_table, $data );
        }
    }

    function get_meta($user_id, $meta_key) {
        $users_meta_table = $this->db->dbprefix('users_meta');

        //prepare full query string
        $sql = "SELECT $users_meta_table.* 
            FROM $users_meta_table
            WHERE $users_meta_table.user_id='$user_id'
                AND $users_meta_table.meta_key='$meta_key'
                LIMIT 1";
        $result = $this->db->query($sql);

        if(!$row = $result->row()) {
            return "";
        }

        return $row->meta_val;
    }

    function authenticate($email, $password, $return_id = false) {

        $email = $this->db->escape_str($email);

        $this->db->select("id,is_admin,user_type,client_id,password,access_erpat");
        $result = $this->db->get_where($this->table, array('email' => $email, 'status' => 'active', 'deleted' => 0, 'disable_login' => 0));

        if ($result->num_rows() !== 1) {
            return false;
        }

        $user_info = $result->row();

        if(!$user_info->is_admin && $user_info->user_type === "staff" && !$user_info->access_erpat) {
            return false;
        }

        //there has two password encryption method for legacy (md5) compatibility
        //check if anyone of them is correct
        if ((strlen($user_info->password) === 60 && password_verify($password, $user_info->password)) || $user_info->password === md5($password)) {

            if ($this->_client_can_login($user_info) !== false) {
                if($return_id) {
                    return $user_info->id;
                } else {
                    $this->session->set_userdata('user_id', $user_info->id);
                    return true;
                }
            }
        }
    }

    private function _client_can_login($user_info) {
        //check client login settings
        if ($user_info->user_type === "client" && get_setting("disable_client_login")) {
            return false;
        } else if ($user_info->user_type === "client") {
            //user can't be loged in if client has deleted
            $clients_table = $this->db->dbprefix('clients');

            $sql = "SELECT $clients_table.id
                    FROM $clients_table
                    WHERE $clients_table.id = $user_info->client_id AND $clients_table.deleted=0";
            $client_result = $this->db->query($sql);

            if ($client_result->num_rows() !== 1) {
                return false;
            }
        }
    }

    function login_user_id() {
        $login_user_id = $this->session->user_id;
        return $login_user_id ? $login_user_id : false;
    }

    function sign_out() {
        $this->session->sess_destroy();
        redirect('signin');
    }

    function get_details($options = array()) {
        $users_table = $this->db->dbprefix('users');
        $team_table = $this->db->dbprefix('team');
        $schedule_table = $this->db->dbprefix('schedule');
        $team_member_job_info_table = $this->db->dbprefix('team_member_job_info');
        $clients_table = $this->db->dbprefix('clients');
        $teams_table = $this->db->dbprefix('team');

        $where = "";
        $id = get_array_value($options, "id");
        $status = get_array_value($options, "status");
        $user_type = get_array_value($options, "user_type");
        $client_id = get_array_value($options, "client_id");
        $vendor_id = get_array_value($options, "vendor_id");
        $exclude_user_id = get_array_value($options, "exclude_user_id");
        $search = get_array_value($options, "search");
        $teams = get_array_value($options, "include_teams");

        if ($id) {
            $where .= empty($where) ? " " : " AND";
            $where .= " $users_table.id=$id";
        }

        if ($status === "active") {
            $where .= empty($where) ? " " : " AND";
            $where .= " $users_table.status='active'";
        } else if ($status === "inactive") {
            $where .= empty($where) ? " " : " AND";
            $where .= " $users_table.status='inactive'";
        }

        if ($status === "resigned") {
            $where .= empty($where) ? " " : " AND";
            $where .= " $users_table.resigned='1'";
        }

        if ($status === "terminated") {
            $where .= empty($where) ? " " : " AND";
            $where .= " $users_table.terminated='1'";
        }
        
        $where .= empty($where) ? " " : " AND";
        if ($status === "deleted") {
            $where .= " $users_table.deleted='1'";
        } else {
            $where .= " $users_table.deleted='0'";
        }

        if ($user_type && $user_type !== "sysadmin") {
            $where .= empty($where) ? " " : " AND";
            $where .= " $users_table.user_type='$user_type'";
        }

        if ($user_type && $user_type === "sysadmin") {
            $where .= empty($where) ? " " : " AND";
            $where .= " ($users_table.user_type='system' OR $users_table.is_admin=1)";
        }

        if ($user_type == 'client') {
            $where .= empty($where) ? " " : " AND";
            $where .= " $clients_table.deleted=0";
        }

        if ($client_id) {
            $where .= empty($where) ? " " : " AND";
            $where .= " $users_table.client_id=$client_id";
        }

        if ($vendor_id) {
            $where .= empty($where) ? " " : " AND";
            $where .= " $users_table.vendor_id=$vendor_id";
        }

        if ($exclude_user_id) {
            $where .= empty($where) ? " " : " AND";
            $where .= " $users_table.id!=$exclude_user_id";
        }

        if ($search) {
            $where .= empty($where) ? " " : " AND";
            $where .= " ($users_table.first_name LIKE '%$search%' OR $users_table.last_name LIKE '%$search%' OR $users_table.email LIKE '%$search%' OR $users_table.job_title LIKE '%$search%')";
        }

        $labels = get_array_value($options, "label_id");
        if ($labels) {
            $where .= " AND (FIND_IN_SET('$labels', $users_table.labels)) ";
        }
        $this->use_table("users");
        $select_labels_data_query = $this->get_labels_data_query();

        $teams_ids = "( SELECT GROUP_CONCAT($team_table.id) FROM $team_table WHERE $team_table.deleted='0' AND (FIND_IN_SET($users_table.id, $team_table.heads) OR FIND_IN_SET($users_table.id, $team_table.members) ) )";
        $department_id = get_array_value($options, "department_id");
        if($department_id){
            $where .= " AND FIND_IN_SET('$department_id', $teams_ids) ";
        }

        $sched_id = get_array_value($options, "sched_id");
        if ($sched_id) {
            $where .= " AND $schedule_table.id=$sched_id ";
        }

        $where_in = get_array_value($options, "where_in");
        if ($where_in) {
            $list_user = implode(",", $where_in);
            log_message("error", $where_in);
            $where .= " AND FIND_IN_SET($users_table.id, '$list_user')";
        }
        
        $custom_field_type = "team_members";
        if ($user_type === "client") {
            $custom_field_type = "client_contacts";
        } else if ($user_type === "lead") {
            $custom_field_type = "lead_contacts";
        }

        $teams_lists = ", ( SELECT GROUP_CONCAT($team_table.title) FROM $team_table WHERE $team_table.deleted='0' AND (FIND_IN_SET($users_table.id, $team_table.heads) OR FIND_IN_SET($users_table.id, $team_table.members) ) ) as team_list";

        //prepare custom fild binding query
        $custom_fields = get_array_value($options, "custom_fields");
        $custom_field_query_info = $this->prepare_custom_field_query_string($custom_field_type, $custom_fields, $users_table);
        $select_custom_fieds = get_array_value($custom_field_query_info, "select_string");
        $join_custom_fieds = get_array_value($custom_field_query_info, "join_string");

        $order_by = "ORDER BY $users_table.first_name";
        $randomize = get_array_value($options, "randomize");
        if ($randomize) {
            $order_by = "ORDER BY RAND() LIMIT 1";
        }

        //prepare full query string
        $sql = "SELECT $users_table.*, $schedule_table.id as sched_id, $schedule_table.title as sched_name, $select_labels_data_query, $team_member_job_info_table.rfid_num, $team_member_job_info_table.job_idnum, $team_member_job_info_table.date_of_hire, $team_member_job_info_table.salary, $team_member_job_info_table.salary_term, $team_member_job_info_table.bank_name, $team_member_job_info_table.bank_account, $team_member_job_info_table.bank_number, $team_member_job_info_table.sss, $team_member_job_info_table.tin, $team_member_job_info_table.pag_ibig, $team_member_job_info_table.phil_health, $team_member_job_info_table.signiture_url $teams_lists  $select_custom_fieds
        FROM $users_table
        LEFT JOIN $team_member_job_info_table ON $team_member_job_info_table.user_id=$users_table.id
        LEFT JOIN $schedule_table ON $schedule_table.id=$team_member_job_info_table.sched_id
        LEFT JOIN $clients_table ON $clients_table.id=$users_table.client_id
        $join_custom_fieds    
        WHERE $where
        $order_by";
        return $this->db->query($sql);
    }

    function is_user_active($user_id) {
        $users_table = $this->db->dbprefix('users');

        $sql = "SELECT $users_table.email FROM $users_table   
        WHERE $users_table.deleted=0 AND $users_table.status='active' AND $users_table.user_type='staff' AND $users_table.id=$user_id";

        $result = $this->db->query($sql);

        if ($result->num_rows()) {
            return true;
        } else {
            return false;
        }
    }

    function is_email_exists($email, $id = 0) {
        $users_table = $this->db->dbprefix('users');

        $sql = "SELECT $users_table.* FROM $users_table   
        WHERE $users_table.deleted=0 AND $users_table.email LIKE '$email' AND $users_table.user_type!='lead'";

        $result = $this->db->query($sql);

        if ($result->num_rows() && $result->row()->id != $id) {
            return $result->row();
        } else {
            return false;
        }
    }

    function get_job_info($user_id) {
        parent::use_table("team_member_job_info");
        return parent::get_one_where(array("user_id" => $user_id));
    }

    function save_job_info($data) {
        parent::use_table("team_member_job_info");

        //check if job info already exists
        $where = array("user_id" => get_array_value($data, "user_id"));
        $exists = parent::get_one_where($where);
        if ($exists->user_id) {
            //job info found. update the record
            return parent::update_where($data, $where);
        } else {
            //insert new one
            return parent::save($data);
        }
    }

    function update_role_id($data) {
        $field = array(
            "role_id" => get_array_value($data, "role_id")
        );
        $where = array(
            "id" => get_array_value($data, "user_id"),
        );
        return parent::update_where($field, $where);
    }

    function update_all_user_sched($data) {
        $jobinfo = $this->db->dbprefix('team_member_job_info');
        
        $latest = get_array_value($data, "sched_id");
        $previous = get_array_value($data, "prev_sched_id");

        $sql = "UPDATE $jobinfo SET sched_id=$latest WHERE sched_id=$previous";
        return $this->db->query($sql);
    }

    function get_team_members($member_ids = "") {
        $users_table = $this->db->dbprefix('users');
        $sql = "SELECT $users_table.*
        FROM $users_table
        WHERE $users_table.deleted=0 AND $users_table.status='active' AND $users_table.user_type='staff' AND FIND_IN_SET($users_table.id, '$member_ids')
        ORDER BY $users_table.first_name";
        return $this->db->query($sql);
    }

    function get_baseinfo($user_id = 0) {
        $users_table = $this->db->dbprefix('users');

        $sql = "SELECT $users_table.id, $users_table.uuid, $users_table.user_type, $users_table.is_admin, $users_table.role_id, $users_table.email,
            $users_table.first_name, $users_table.last_name, $users_table.image
        FROM $users_table 
        WHERE $users_table.deleted=0 AND $users_table.id=$user_id";
        return $this->db->query($sql)->row();
    }

    function get_access_info($user_id = 0) {
        $users_table = $this->db->dbprefix('users');
        $roles_table = $this->db->dbprefix('roles');
        $team_table = $this->db->dbprefix('team');

        $sql = "SELECT $users_table.id, $users_table.user_type, $users_table.is_admin, $users_table.role_id, $users_table.email,
            $users_table.first_name, $users_table.last_name, $users_table.image, $users_table.message_checked_at, 
            $users_table.notification_checked_at, $users_table.client_id, $users_table.enable_web_notification, 
            $users_table.is_primary_contact, $users_table.sticky_note, 
            $roles_table.title as role_title, $roles_table.permissions, 
            (SELECT GROUP_CONCAT(id) team_ids FROM $team_table WHERE FIND_IN_SET('$user_id', `members`)) as team_ids
        FROM $users_table
        LEFT JOIN $roles_table ON $roles_table.id = $users_table.role_id AND $roles_table.deleted = 0
        WHERE $users_table.deleted=0 AND $users_table.id=$user_id";
        return $this->db->query($sql)->row();
    }

    function get_team_members_and_clients($user_type = "", $user_ids = "", $exlclude_user = 0) {

        $users_table = $this->db->dbprefix('users');
        $clients_table = $this->db->dbprefix('clients');


        $where = "";
        if ($user_type) {
            $where .= " AND $users_table.user_type='$user_type'";
        } else {
            $where .= " AND $users_table.user_type!='lead'";
        }

        if ($user_ids) {
            $where .= "  AND FIND_IN_SET($users_table.id, '$user_ids')";
        }

        if ($exlclude_user) {
            $where .= " AND $users_table.id !=$exlclude_user";
        }

        $sql = "SELECT $users_table.id,$users_table.client_id, $users_table.user_type, $users_table.first_name, $users_table.last_name, $clients_table.company_name,
            $users_table.image,  $users_table.job_title, $users_table.last_online
        FROM $users_table
        LEFT JOIN $clients_table ON $clients_table.id = $users_table.client_id AND $clients_table.deleted=0
        WHERE $users_table.deleted=0 AND $users_table.status='active' $where
        ORDER BY $users_table.user_type, $users_table.first_name ASC";
        return $this->db->query($sql);
    }

    /* return comma separated list of user names */

    function user_group_names($user_ids = "") {
        $users_table = $this->db->dbprefix('users');

        $fullname = $this->get_fullname_struct("user_group_name");

        $sql = "SELECT $fullname
            FROM $users_table
            WHERE FIND_IN_SET($users_table.id, '$user_ids')";
        return $this->db->query($sql)->row();
    }

    /* return list of ids of the online users */

    function get_online_user_ids() {
        $users_table = $this->db->dbprefix('users');
        $now = get_current_utc_time();

        $sql = "SELECT $users_table.id 
        FROM $users_table
        WHERE TIMESTAMPDIFF(MINUTE, users.last_online, '$now')<=0";
        return $this->db->query($sql)->result();
    }

    function get_active_members_and_clients($options = array()) {
        $users_table = $this->db->dbprefix('users');
        $clients_table = $this->db->dbprefix('clients');

        $where = "";

        $user_type = get_array_value($options, "user_type");
        if ($user_type) {
            $where .= " AND $users_table.user_type='$user_type'";
        }

        $exclude_user_id = get_array_value($options, "exclude_user_id");
        if ($exclude_user_id) {
            $where .= " AND $users_table.id!=$exclude_user_id";
        }

        $fullname = $this->get_fullname_struct("member_name");

        $sql = "SELECT $fullname, $users_table.last_online, $users_table.id, $users_table.image, $users_table.job_title, $users_table.user_type, $clients_table.company_name
        FROM $users_table
        LEFT JOIN $clients_table ON $clients_table.id = $users_table.client_id AND $clients_table.deleted=0
        WHERE $users_table.deleted=0 $where
        ORDER BY $users_table.last_online DESC";
        return $this->db->query($sql);
    }

    function count_total_contacts() {
        $users_table = $this->db->dbprefix('users');
        $sql = "SELECT COUNT($users_table.id) AS total
        FROM $users_table 
        WHERE $users_table.deleted=0 AND $users_table.user_type='client'";
        return $this->db->query($sql)->row()->total;
    }

    function get_team_members_for_select2(){
        $users_table = $this->db->dbprefix('users');
        
        $fullname = $this->get_fullname_struct();

        $sql = "SELECT $users_table.id, $fullname
            FROM $users_table
            WHERE $users_table.deleted=0 AND $users_table.user_type='staff'
                ORDER BY $users_table.first_name";
        return $this->db->query($sql);
    }

    function get_users_without_uuid() {
        $users_table = $this->db->dbprefix('users');

        $sql = "SELECT $users_table.id 
        FROM $users_table
        WHERE uuid = '' OR uuid IS NULL";
        return $this->db->query($sql)->result();
    }

    function set_user_uuid($user_id, $new_uuid) {
        $users_table = $this->db->dbprefix('users');

        $sql = "UPDATE $users_table 
        SET uuid='$new_uuid' 
        WHERE id = '$user_id'";
        return $this->db->query($sql);
    }

    function changepass($user_id, $old_password, $new_password) {

        $oldpass = $this->db->escape_str($old_password);
        $newpass = $this->db->escape_str($new_password);

        $this->table = $this->db->dbprefix('users');
        $this->db->select("password");
        $result = $this->db->get_where($this->table, array('id' => $user_id, 'status' => 'active', 'deleted' => 0, 'disable_login' => 0));

        if ($result->num_rows() !== 1) {
            return false;
        }

        $user_info = $result->row();
        if ((strlen($user_info->password) === 60 && password_verify($oldpass, $user_info->password)) || $user_info->password === md5($oldpass)) {
            $data = array("password" => password_hash($newpass, PASSWORD_DEFAULT) );
            $where = array("id" => $user_id);
            return parent::update_where($data, $where);
        }

        return false;
    }

    function get_actual_active($user_ids = "") {
        $users_table = $this->db->dbprefix('users');

        $sql = "SELECT COUNT(id) AS total_active
        FROM $users_table
        WHERE $users_table.deleted='0' AND $users_table.status='active' AND $users_table.user_type='staff' AND FIND_IN_SET($users_table.id, '$user_ids')";
        return $this->db->query($sql)->row()->total_active;
    }

    function get_all_active($options = array()) {
        $users = $this->db->dbprefix('users');
        $job_info = $this->db->dbprefix('team_member_job_info');
        $users_meta = $this->db->dbprefix('users_meta');

        $fields = "";
        $from = "";

        $date_hired = get_array_value($options, "date_hired");
        if ($date_hired) {
            $fields .= ", $job_info.date_of_hire as date_hired";
            $from .= " LEFT JOIN $job_info ON $job_info.user_id=$users.id ";
        }

        $is_regular = get_array_value($options, "is_regular");
        if ($is_regular) {
            $fields .= ", $users_meta.meta_val as employment_stage";
            $from .= " INNER JOIN $users_meta ON $users_meta.user_id=$users.id 
                AND $users_meta.meta_key='employment_stage' 
                AND $users_meta.meta_val='regular' ";
        }

        $fullname = $this->get_fullname_struct();

        $sql = "SELECT {$users}.id, $fullname $fields
        FROM $users $from
        WHERE {$users}.deleted=0 
            AND {$users}.terminated=0 
            AND {$users}.resigned=0 
            AND {$users}.status='active' 
            AND {$users}.user_type='staff' ";
        return $this->db->query($sql)->result();
    }

    protected function get_fullname_struct($field_name = "user_name") {
        $users = $this->db->dbprefix('users');

        $fullname = "CONCAT($users.first_name, ' ', $users.last_name) AS $field_name";
        if(get_setting('name_format') == "lastfirst") {
            $fullname = "CONCAT($users.last_name, ', ', $users.first_name) AS $field_name";
        }

        return $fullname;
    }
}
