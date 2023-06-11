<?php

class Raffle_draw_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'event_raffle';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $event_raffle_table = $this->db->dbprefix('event_raffle');
        $start = get_array_value($options, "start");
        $end = get_array_value($options, "end");
        $where = "";

        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $event_raffle_table.id=$id";
        }

        if($start && $end){
            $where .= " AND DATE({$this->table}.draw_date) BETWEEN '$start' AND '$end'";
        }

        $event_id = get_array_value($options, "event_id");
        if ($event_id) {
            $where .= " AND $event_raffle_table.event_id=$event_id";
        }

        $status = get_array_value($options, "status");
        if ($status) {
            $where .= " AND $event_raffle_table.status='$status'";
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

        if( isset($data['user_id']) ) {
            $current = $this->get_participants(array(
                "raffle_id" => $data['raffle_id'],
                "user_id" => $data['user_id']
            ));
    
            //Check if user id is not on the participant list.
            if(count($current->result()) == 0) {
                $insert_id = $this->db->insert($event_raffle_participants_table, $data);
                return $insert_id;
            } 
        } else {
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

        $uuid = get_array_value($options, "uuid");
        if ($uuid) {
            $where .= " AND $event_raffle_participants_table.uuid='$uuid'";
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

    function set_participant($id, $user_id, $remarks) {
        $event_raffle_participants_table = $this->db->dbprefix('event_raffle_participants');

        $sql = "Update $event_raffle_participants_table 
            SET user_id = '$user_id', remarks = '$remarks'
            WHERE id = $id";
        return $this->db->query($sql);
    }

    function get_winners($options = array()) {
        $event_raffle_winner_table = $this->db->dbprefix('event_raffle_winners');
        $event_raffle_table = $this->db->dbprefix('event_raffle');
        $event_raffle_participants = $this->db->dbprefix('event_raffle_participants');
        $users_table = $this->db->dbprefix('users');

        $where = "";

        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $event_raffle_winner_table.id='$id'";
        }

        $raffle_id = get_array_value($options, "raffle_id");
        if ($raffle_id) {
            $where .= " AND $event_raffle_table.id='$raffle_id'";
        }

        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $event_raffle_winner_table.user_id=$user_id";
        }

        $order_by = get_array_value($options, "order_by");
        if ($order_by) {
            $where .= " AND $users_table.id IS NOT NULL ORDER BY updated_at DESC LIMIT 20";
        }

        $sql = "SELECT $event_raffle_winner_table.*, 
            $event_raffle_table.title as raffle_name,
            $event_raffle_participants.uuid as participants_uuid, 
            $users_table.id as user_id,
            events.title as event_name, 
            TRIM(CONCAT($users_table.first_name, ' ', $users_table.last_name)) AS user_name
        FROM $event_raffle_winner_table
            INNER JOIN $event_raffle_participants ON $event_raffle_participants.id = $event_raffle_winner_table.participant_id
            INNER JOIN $event_raffle_table ON $event_raffle_table.id = $event_raffle_participants.raffle_id AND $event_raffle_table.status = 'active'
            LEFT JOIN events ON events.id = $event_raffle_table.event_id 
            LEFT JOIN $users_table ON $users_table.id = $event_raffle_participants.user_id
        WHERE $event_raffle_winner_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function pick_winners($options = array()) {
        $event_raffle_participants_table = $this->db->dbprefix('event_raffle_participants');
        $event_raffle_winner_table = $this->db->dbprefix('event_raffle_winners');

        $raffle_id = get_array_value($options, "raffle_id");
        $winners = (int)(get_array_value($options, "winners")?get_array_value($options, "winners"):1);

        $sql = "SELECT $event_raffle_participants_table.*, 
            TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS user_name 
            FROM $event_raffle_participants_table
                LEFT JOIN users ON users.id = $event_raffle_participants_table.user_id
            WHERE $event_raffle_participants_table.deleted = '0' AND 
                $event_raffle_participants_table.raffle_id = $raffle_id AND 
                $event_raffle_participants_table.id NOT IN (
                    SELECT $event_raffle_winner_table.participant_id 
                    FROM $event_raffle_winner_table 
                    WHERE $event_raffle_winner_table.raffle_id = $event_raffle_participants_table.raffle_id AND 
                    $event_raffle_winner_table.deleted = 0
                )
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
