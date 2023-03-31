<?php

class Schedule_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'schedule';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $schedule = $this->db->dbprefix('schedule');
        
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
        
        $sql = "SELECT $schedule.*, 
                TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS creator_name
            FROM $schedule 
                LEFT JOIN users ON users.id = $schedule.created_by
            WHERE $where";
        return $this->db->query($sql);
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
