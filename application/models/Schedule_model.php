<?php

class Schedule_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'schedule';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $schedule = $this->db->dbprefix('schedule');
        $job_info = $this->db->dbprefix('team_member_job_info');
        
        $fields= "";
        $where= " true ";

        $deleted=get_array_value($options, "deleted");
        if($deleted){
            $where .= " AND ($schedule.deleted=0 OR $schedule.deleted=1)";
        } else {
            $where .= " AND $schedule.deleted=0";
        }

        $id=get_array_value($options, "id");
        if($id){
            $where .=" AND $schedule.id=$id";
        }

        $assigned_to=get_array_value($options, "assigned_to");
        if($assigned_to){
            $fields .=", (SELECT GROUP_CONCAT($job_info.user_id) FROM $job_info WHERE $job_info.sched_id=$schedule.id) as assigned";
        }
        
        $sql = "SELECT $schedule.*, 
                TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS creator_name
                $fields
            FROM $schedule 
                LEFT JOIN users ON users.id = $schedule.created_by
            WHERE $where";
        return $this->db->query($sql);
    }

    function get_user_without_schedule() {
        $users = $this->db->dbprefix('users');
        $job_info = $this->db->dbprefix('team_member_job_info');
        $schedule = $this->db->dbprefix('schedule');

        $sql = "SELECT GROUP_CONCAT($users.id) as user_ids
            FROM $users 
                INNER JOIN $job_info as job ON job.user_id = $users.id
                LEFT JOIN $schedule sched ON sched.id = job.sched_id AND sched.deleted=0
            WHERE $users.deleted=0 AND 
                $users.status='active' AND 
                $users.disable_login=0 AND 
                sched.id IS NULL ";

        $query = $this->db->query($sql);

        if($query->num_rows()) {
            return $query->row()->user_ids;
        }

        return "";
    }

    function getUserSchedId($user_id) {
        $job_info = $this->db->dbprefix('team_member_job_info');
        $sql = "SELECT sched_id FROM $job_info 
            WHERE deleted=0 AND user_id='$user_id'";
        $row = $this->db->query($sql)->row();

        if( isset( $row ) ) {
            return $row->sched_id;
        } else {
            return false;
        }
    }

}
