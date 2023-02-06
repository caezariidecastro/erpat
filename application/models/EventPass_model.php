<?php

class EventPass_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'event_pass';
        parent::__construct($this->table);
        $this->load->model('Email_templates_model');
    }

    function get_details($options = array()) {
        $event_pass_table = $this->db->dbprefix('event_pass');
        $where = " WHERE $event_pass_table.deleted=0 ";

        $deleted = get_array_value($options, "deleted");
        if ($deleted && $deleted == true) {
            $where = " WHERE $event_pass_table.deleted=1 ";
        }

        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $event_pass_table.id=$id";
        }

        $uuid = get_array_value($options, "uuid");
        if ($uuid) {
            $where .= " AND $event_pass_table.uuid='$uuid'";
        }

        $guest = get_array_value($options, "guest");
        if ($guest) {
            $where .= " AND $event_pass_table.guest='$guest'";
        }

        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND users.id = $user_id";
        }

        $event_id = get_array_value($options, "event_id");
        if ($event_id) {
            $where .= " AND $event_pass_table.event_id = $event_id";
        }

        $status = get_array_value($options, "status");
        if ($status) {
            $where .= " AND $event_pass_table.status = '$status'";
        }

        $type = get_array_value($options, "type");
        if ($type == 'companion') {
            $where .= " AND $event_pass_table.guest IS NOT NULL";
        } else if($type == 'override') {
            $where .= " AND $event_pass_table.override='2'";
        } else {
            $where .= " AND $event_pass_table.guest IS NULL";
        }

        if($search = get_array_value($options, "search")) {
            $search_query_start = " AND ( ";
            $search_query_end = " )";
            $search_lists = "";

            $searches = explode(" ", $search);
            foreach($searches as $item) {
                if(!empty($search_lists)) {
                    $search_lists .= " OR ";
                }
                $search_lists .= " $event_pass_table.uuid LIKE '%$item%' ";
                $search_lists .= " OR first_name LIKE '%$item%' ";
                $search_lists .= " OR last_name LIKE '%$item%' ";
            }
            
            if(!empty($search_lists)) {
                $where .= $search_query_start.$search_lists.$search_query_end;
            }
        }

        $groups = get_array_value($options, "groups");
        if ($groups) {
            $where .= " AND $event_pass_table.group_name = '$groups'";
        }

        $limit = "";
        $limits = get_array_value($options, "limits");
        if ($limits) {
            $limit = " LIMIT $limits";
        }

        $sql = "SELECT $event_pass_table.*, users.phone, users.email as user_email, users.id as user_id, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS full_name, events.title as event_name, group_concat(TRIM(CONCAT(seats.area_name, ' (', seats.block_name, ') ', seats.seat_name)) SEPARATOR '\n') as assign, users.first_name, users.last_name
        FROM $event_pass_table 
            LEFT JOIN (
                SELECT epass_seat.id as id, epass_seat.seat_name as seat_name, epass_area.event_id as event_id, epass_area.area_name as area_name, epass_block.block_name as block_name
                FROM epass_seat
                    INNER JOIN epass_block ON epass_block.id = epass_seat.block_id 
                    INNER JOIN epass_area ON epass_area.id = epass_block.area_id
                WHERE epass_seat.deleted = '0' 
            ) as seats ON seats.event_id = $event_pass_table.event_id AND FIND_IN_SET(seats.id, $event_pass_table.seat_assign)
            LEFT JOIN users ON users.id = $event_pass_table.user_id AND users.deleted = 0
            LEFT JOIN events ON events.id = $event_pass_table.event_id AND events.deleted = 0
            
        $where GROUP BY $event_pass_table.uuid ORDER BY $event_pass_table.id ASC $limit ";

        return $this->db->query($sql);
    }

    function event_verify() {
        $content = '<div style="background-color: #eeeeef; padding: 50px 0; ">    <div style="max-width:640px; margin:0 auto; ">  <div style="color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;"><h1>Ticket Confirmation</h1> </div> <div style="padding: 20px; background-color: rgb(255, 255, 255);">            <p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;">Hello Brilliant,</span><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><span style="font-weight: bold;"><br></span></span></p>            <p style=""><br></p><p><font color="#555555"><span style="font-size: 14px;">We are happy to inform you that your booking for Brilliant Skin Essentials Inc. -#PINAKAMAKINANG “The Brilliant Concert 2023 is now under processing! Get ready to witness the BRIGHTEST event of the year.</span></font></p><p><font color="#555555"><span style="font-size: 14px;"><br>Title:&nbsp;</span></font><span style="color: rgb(85, 85, 85); font-size: 14px;">#PINAKAMAKINANG “The Brilliant Concert 2023</span></p><p><font color="#555555"><span style="font-size: 14px;">Date: 07 February 2023</span></font></p><p><font color="#555555"><span style="font-size: 14px;">Time: 4:00 PM</span></font></p><p><font color="#555555"><span style="font-size: 14px;"><br></span></font></p><p><font color="#555555"><span style="font-size: 14px;">
        </span></font></p><p><font color="#555555"><span style="font-size: 14px;"><br></span></font></p><p><font color="#555555"><span style="font-size: 14px;">Participant`s details:</span></font></p><p><span style="color: rgb(85, 85, 85); font-size: 14px;">Reference ID: {REFERENCE_ID}</span></p><p><font color="#555555"><span style="font-size: 14px;">Group : {GROUP_NAME}<br></span></font></p><p><font color="#555555"><span style="font-size: 14px;">Name: {FIRST_NAME} {LAST_NAME}</span></font></p><p><font color="#555555"><span style="font-size: 14px;">Phone: {PHONE_NUMBER}</span></font></p><p><span style="color: rgb(85, 85, 85); font-size: 14px;">Number of Seats: {TOTAL_SEATS}</span><font color="#555555"><span style="font-size: 14px;"><br></span></font></p><p><font color="#555555"><span style="font-size: 14px;">Remarks: {REMARKS}</span></font></p><p><font color="#555555"><span style="font-size: 14px;"><br></span></font></p><p><font color="#555555"><span style="font-size: 14px;">Event location:</span></font></p><p><font color="#555555"><b style="font-size: 14px;">Smart Araneta Coliseum</b><br><span style="font-size: 14px;">General Roxas Ave, Araneta City, QC, 1109 Metro Manila</span></font></p><p><font color="#555555"><span style="font-size: 14px;"><br></span></font></p><p><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><a href="https://goo.gl/maps/P7gXh8FEMLjPSxUH6" target="_blank" style="background-color: rgb(0, 179, 147); color: rgb(255, 255, 255); padding: 10px 15px;">Open on Google Map</a></span></p><div><br></div><p><font color="#555555"><span style="font-size: 14px;"><br></span></font></p><p><font color="#555555"><span style="font-size: 14px;">We can’t wait to see you!</span></font></p><p style=""><br></p>            <p style="color: rgb(85, 85, 85); font-size: 14px;">{SIGNATURE}</p>        </div>    </div></div>';

        $template_name = 'event_pass';
        $email_subject = 'e-Pass Verification';

        //Try to get the id and just update.
        $template = $this->Email_templates_model->get_one_where(array(
            "template_name" => $template_name 
        ));

        //If the id is null, create new one.
        if(!$template->id) {
            return $this->Email_templates_model->new_template($template_name, $email_subject, $content);
        }

        $data = array(
            "email_subject" => $email_subject,
            "default_message" => $content,
        );

        return $this->Email_templates_model->save($data, $template->id);
    }

    function event_confirm() {
        $content = '<div style="background-color: #eeeeef; padding: 50px 0; ">    <div style="max-width:640px; margin:0 auto; ">  <div style="color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;"><h1>Ticket Confirmation</h1> </div> <div style="padding: 20px; background-color: rgb(255, 255, 255);">            <p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;">Hello Brilliant,</span></p><p><font color="#555555"><span style="font-size: 14px;">We are happy to inform you that your booking for Brilliant Skin Essentials Inc. -#PINAKAMAKINANG “The Brilliant Concert 2023 is now <b>approved </b>and <b>reserved</b>! Get ready to witness the BRIGHTEST event of the year.</span></font></p><p><font color="#555555"><span style="font-size: 14px;"><br><b>Title:&nbsp;</b></span></font><span style="color: rgb(85, 85, 85); font-size: 14px;"><b>#PINAKAMAKINANG “The Brilliant Concert 2023</b></span></p><p><font color="#555555"><span style="font-size: 14px;"><b>Date: 07 February 2023</b></span></font></p><p><font color="#555555"><span style="font-size: 14px;"><b>Time: 4:00 PM</b></span></font></p><p><br></p><p><font color="#555555"><span style="font-size: 14px;"><b>Guest`s details</b>:</span></font></p><p><span style="color: rgb(85, 85, 85); font-size: 14px;">Reference ID: {REFERENCE_ID}</span></p><p><font color="#555555"><span style="font-size: 14px;">Group : {GROUP_NAME}<br></span></font></p><p><font color="#555555"><span style="font-size: 14px;">Name: {FIRST_NAME} {LAST_NAME}</span></font></p><p><font color="#555555"><span style="font-size: 14px;">Phone: {PHONE_NUMBER}</span></font></p><p><span style="color: rgb(85, 85, 85); font-size: 14px;">Number of Seats: {TOTAL_SEATS}</span></p><p><span style="color: rgb(85, 85, 85); font-size: 14px;">Companion`s Link: {COMPANION_LINK}</span><span style="color: rgb(85, 85, 85); font-size: 14px;"><br></span></p><p><span style="font-size: 14px; color: rgb(85, 85, 85);">Remarks: {REMARKS}</span><span style="color: rgb(85, 85, 85); font-size: 14px;"><br></span></p><p><font color="#555555"><span style="font-size: 14px;"><br></span></font></p><p><font color="#555555"><span style="font-size: 14px;"><b>Event location:</b></span></font></p><p><font color="#555555"><span style="font-size: 14px;">Smart Araneta Coliseum</span><br><span style="font-size: 14px;"><i>General Roxas Ave, Araneta City, QC, 1109 Metro Manila</i></span></font></p><p><font color="#555555"><span style="font-size: 14px;"><br></span></font></p><p><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><a href="https://goo.gl/maps/P7gXh8FEMLjPSxUH6" target="_blank" style="background-color: rgb(0, 179, 147); color: rgb(255, 255, 255); padding: 10px 15px;">Open on Google Map</a></span></p><div><br></div><p><font color="#555555"><span style="font-size: 14px;"><br></span></font></p><p><font color="#555555"><span style="font-size: 14px;">We can’t wait to see you!</span></font></p><p style=""><br></p>            <p style="color: rgb(85, 85, 85); font-size: 14px;">{SIGNATURE}</p>        </div>    </div></div>';

        $template_name = 'epass_confirm';
        $email_subject = 'e-Pass Confirmation';

        //Try to get the id and just update.
        $template = $this->Email_templates_model->get_one_where(array(
            "template_name" => $template_name 
        ));

        //If the id is null, create new one.
        if(!$template->id) {
            return $this->Email_templates_model->new_template($template_name, $email_subject, $content);
        }

        $data = array(
            "email_subject" => $email_subject,
            "default_message" => $content,
        );

        return $this->Email_templates_model->save($data, $template->id);
    }

    function unassign_all_approved() {
        $event_pass_table = $this->db->dbprefix('event_pass');

        $sql = "UPDATE $event_pass_table 
            SET seat_assign=''
            WHERE $event_pass_table.deleted=0";

        return $this->db->query($sql);
    }

    function get_all_approved($group_name, $extra = "") {
        $event_pass_table = $this->db->dbprefix('event_pass');

        $sql = "SELECT $event_pass_table.*
            FROM $event_pass_table 
            WHERE $event_pass_table.deleted=0 AND $event_pass_table.status='approved' AND group_name='$group_name' 
                $extra
            ORDER BY timestamp ASC";

        return $this->db->query($sql)->result();
    }

    function get_all_sent() {
        $event_pass_table = $this->db->dbprefix('event_pass');

        $sql = "SELECT $event_pass_table.*
            FROM $event_pass_table 
            WHERE $event_pass_table.deleted=0 AND $event_pass_table.status='sent' 
                AND guest IS NULL $extra
            ORDER BY timestamp ASC";

        return $this->db->query($sql)->result();
    }

    function get_all_unassigned() {
        $event_pass_table = $this->db->dbprefix('event_pass');

        $sql = "SELECT $event_pass_table.*
            FROM $event_pass_table 
            WHERE $event_pass_table.deleted='0' 
                AND ($event_pass_table.status='approved' OR $event_pass_table.status='sent') 
                AND $event_pass_table.seat_assign ='' ORDER BY `event_pass`.`id` ASC";

        return $this->db->query($sql)->result();
    }

    function get_all_unsent_companion() {
        $event_pass_table = $this->db->dbprefix('event_pass');

        $sql = "SELECT $event_pass_table.*
            FROM $event_pass_table 
            WHERE $event_pass_table.deleted='0' 
                AND $event_pass_table.guest IS NOT NULL
                AND ($event_pass_table.status='draft' OR $event_pass_table.status='approved')
                AND $event_pass_table.seat_assign IS NULL 
                ORDER BY `event_pass`.`guest` ASC";

        return $this->db->query($sql)->result();
    }
}
