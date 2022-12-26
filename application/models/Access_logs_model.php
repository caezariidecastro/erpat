<?php

class Access_logs_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'access_logs';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $access_logs_table = $this->db->dbprefix('access_logs');
        $where = " WHERE $access_logs_table.deleted=0 ";

        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $access_logs_table.id=$id";
        }

        $device_id = get_array_value($options, "device_id");
        if ($device_id) {
            $where .= " AND $access_logs_table.device_id=$device_id";
        }

        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $access_logs_table.user_id=$user_id";
        }

        $offset = convert_seconds_to_time_format(get_timezone_offset());
        $start_date = get_array_value($options, "start_date");
        if ($start_date) {
            $where .= " AND DATE(ADDTIME($access_logs_table.timestamp,'$offset'))>='$start_date'";
        }
        $end_date = get_array_value($options, "end_date");
        if ($end_date) {
            $where .= " AND DATE(ADDTIME($access_logs_table.timestamp,'$offset'))<='$end_date'";
        }

        $sql = "SELECT $access_logs_table.*, access_devices.device_name as device_name, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS full_name
        FROM $access_logs_table 
            LEFT JOIN access_devices ON access_devices.id = $access_logs_table.device_id
            LEFT JOIN users ON users.id = $access_logs_table.user_id
        $where ORDER BY timestamp DESC";

        return $this->db->query($sql);
    }
}
