<?php

class Team_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'team';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $team_table = $this->db->dbprefix('team');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $team_table.id=$id";
        }

        $sql = "SELECT $team_table.*, TRIM(CONCAT(emp.first_name, ' ', emp.last_name)) AS creator_name
        FROM $team_table
        LEFT JOIN users emp ON emp.id = $team_table.created_by
        WHERE $team_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function get_members($team_ids = array()) {
        $team_table = $this->db->dbprefix('team');
        $team_ids = implode(",", $team_ids);

        $sql = "SELECT $team_table.heads, $team_table.members
        FROM $team_table
        WHERE $team_table.deleted=0 AND id in($team_ids)";
        return $this->db->query($sql);
    }

    function get_teams($user_id) {
        $team_table = $this->db->dbprefix('team');

        $sql = "SELECT $team_table.title
        FROM $team_table
        WHERE $team_table.deleted=0 AND (FIND_IN_SET('$user_id', $team_table.heads) OR FIND_IN_SET('$user_id', $team_table.members))";
        return $this->db->query($sql);
    }

}
