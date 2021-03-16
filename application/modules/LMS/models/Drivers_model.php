<?php

class Drivers_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'users';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $users_table = $this->db->dbprefix('users');
        $where = "";
        $id = get_array_value($options, "id");

        if ($id) {
            $where .= " AND $users_table.id=$id";
        }

        $sql = "SELECT $users_table.*, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS full_name, TRIM(CONCAT(creator.first_name, ' ', creator.last_name)) AS creator_name, team_member_job_info.date_of_hire
        , COALESCE(
           (
               SELECT COUNT(deliveries.id)
               FROM deliveries
               WHERE deliveries.driver = $users_table.id
           )  
        , 0) AS total_deliveries
        FROM $users_table
        LEFT JOIN users creator ON creator.id = users.created_by
        LEFT JOIN team_member_job_info ON team_member_job_info.user_id = $users_table.id
        WHERE $users_table.deleted=0 $where
        AND $users_table.user_type = 'driver'";
        return $this->db->query($sql);
    }


    function get_driver($id) {
        $users_table = $this->db->dbprefix('users');

        $sql = "SELECT $users_table.*, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS full_name, TRIM(CONCAT(creator.first_name, ' ', creator.last_name)) AS creator_name, team_member_job_info.date_of_hire
        , COALESCE(
           (
               SELECT COUNT(deliveries.id)
               FROM deliveries
               WHERE deliveries.driver = $users_table.id
           )  
        , 0) AS total_deliveries
        FROM $users_table
        LEFT JOIN users creator ON creator.id = users.created_by
        LEFT JOIN team_member_job_info ON team_member_job_info.user_id = $users_table.id
        WHERE $users_table.deleted=0 
        AND $users_table.id = $id
        AND $users_table.user_type = 'driver'";
        return $this->db->query($sql);
    }
}
