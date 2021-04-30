<?php

class Schedule_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'schedule';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $schedule = $this->db->dbprefix('schedule');
        
        $where= "";
        $id=get_array_value($options, "id");
        if($id){
            $where =" AND $schedule.id=$id";
        }
        
        $sql = "SELECT $schedule.*, 
                TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS creator_name
            FROM $schedule 
                LEFT JOIN users ON users.id = $schedule.created_by
            WHERE $schedule.deleted=0 $where";
        return $this->db->query($sql);
    }

}
