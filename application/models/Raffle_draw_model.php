<?php

class Raffle_draw_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'event_raffle';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $event_raffle_table = $this->db->dbprefix('event_raffle');
        $where = "";

        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $event_raffle_table.id=$id";
        }

        $labels = get_array_value($options, "label_id"); //not yet implemented
        if ($labels) {
            $where .= " AND (FIND_IN_SET('$labels', $event_raffle_table.labels)) ";
        }

        $select_labels_data_query = $this->get_labels_data_query();

        $sql = "SELECT $event_raffle_table.*, 
            users.id as users, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS user_name, users.id as user_id,
            events.id as event_id,  events.title as event_name, $select_labels_data_query
        FROM $event_raffle_table
            LEFT JOIN users ON users.id = $event_raffle_table.creator
            LEFT JOIN events ON events.id = $event_raffle_table.event_id 
        WHERE $event_raffle_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function join_raffle($data = array()) {
        $event_raffle_participants_table = $this->db->dbprefix('event_raffle_participants');

        $current = $this->get_participants(array(
            "raffle_id" => $data['raffle_id'],
            "user_id" => $data['user_id']
        ));

        //Check if user id is not on the participant list.
        if(count($current->result()) == 0) {
            $insert_id = $this->db->insert($event_raffle_participants_table, $data);
            return $insert_id;
        } 

        return false;
    }

    function get_participants($options = array()) {
        $event_raffle_participants_table = $this->db->dbprefix('event_raffle_participants');
        $event_raffle_table = $this->db->dbprefix('event_raffle');
        $where = "";

        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $event_raffle_participants_table.id=$id";
        }

        $raffle_id = get_array_value($options, "raffle_id");
        if ($raffle_id) {
            $where .= " AND $event_raffle_table.id=$raffle_id";
        }

        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $event_raffle_participants_table.user_id=$user_id";
        }

        $sql = "SELECT $event_raffle_participants_table.*, $event_raffle_table.title as raffle_name,
        users.id as users, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS user_name, users.id as user_id
        FROM $event_raffle_participants_table
            LEFT JOIN users ON users.id = $event_raffle_participants_table.user_id
            LEFT JOIN $event_raffle_table ON $event_raffle_table.id = $event_raffle_participants_table.raffle_id 
        WHERE $event_raffle_participants_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function get_winners($options = array()) {
        $event_raffle_winner_table = $this->db->dbprefix('event_raffle_winners');
        $event_raffle_table = $this->db->dbprefix('event_raffle');
        $where = "";

        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $event_raffle_winner_table.id=$id";
        }

        $raffle_id = get_array_value($options, "raffle_id");
        if ($raffle_id) {
            $where .= " AND $event_raffle_table.id=$raffle_id";
        }

        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $event_raffle_winner_table.user_id=$user_id";
        }

        $sql = "SELECT $event_raffle_winner_table.*, $event_raffle_table.title as raffle_name,
        users.id as users, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS user_name, users.id as user_id
        FROM $event_raffle_winner_table
            LEFT JOIN users ON users.id = $event_raffle_winner_table.user_id
            LEFT JOIN $event_raffle_table ON $event_raffle_table.id = $event_raffle_winner_table.raffle_id 
        WHERE $event_raffle_winner_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function pick_winners($options = array()) {
        $event_raffle_participants_table = $this->db->dbprefix('event_raffle_participants');
        $event_raffle_winner_table = $this->db->dbprefix('event_raffle_winners');

        $raffle_id = get_array_value($options, "raffle_id");
        $winners = (int)(get_array_value($options, "winners")?get_array_value($options, "winners"):1);

        $sql = "SELECT *, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS user_name 
            FROM $event_raffle_participants_table
                LEFT JOIN users ON users.id = $event_raffle_participants_table.user_id
            WHERE $event_raffle_participants_table.raffle_id = $raffle_id AND 
                user_id NOT IN (SELECT $event_raffle_winner_table.user_id FROM $event_raffle_winner_table WHERE $event_raffle_winner_table.raffle_id = $event_raffle_participants_table.raffle_id AND $event_raffle_winner_table.deleted = 0)
            ORDER BY RAND()
            LIMIT $winners";
        return $this->db->query($sql);
    }

    function save_winner($data = array()) {
        $event_raffle_winner_table = $this->db->dbprefix('event_raffle_winners');

        return $this->db->insert($event_raffle_winner_table, $data);
    }

    function clear_winners($raffle_id) {
        $event_raffle_winner_table = $this->db->dbprefix('event_raffle_winners');
        $sql = "UPDATE $event_raffle_winner_table SET deleted=1 WHERE raffle_id = '$raffle_id'";
        return $this->db->query($sql);
    }

    function clear_participants($raffle_id) {
        $event_raffle_participants_table = $this->db->dbprefix('event_raffle_participants');
        $sql = "UPDATE $event_raffle_participants_table SET deleted=1 WHERE raffle_id = '$raffle_id'";
        return $this->db->query($sql);
    }
    
}
