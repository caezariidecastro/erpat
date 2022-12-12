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
        $where = "";
        $id = get_array_value($options, "id");

        if ($id) {
            $where .= " AND $event_pass_table.id=$id";
        }

        $sql = "SELECT $event_pass_table.*, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS full_name
        FROM $event_pass_table 
            LEFT JOIN users ON users.id = $event_pass_table.user_id
        WHERE $event_pass_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function save_email() {
        $content = '<div style="background-color: #eeeeef; padding: 50px 0; ">    <div style="max-width:640px; margin:0 auto; ">  <div style="color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;"><h1>Ticket Confirmation</h1> </div> <div style="padding: 20px; background-color: rgb(255, 255, 255);">            <p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;">Hello Brilliant,</span><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><span style="font-weight: bold;"><br></span></span></p>            <p style=""><br></p><p><font color="#555555"><span style="font-size: 14px;">We are happy to inform you that your booking for Brilliant Skin Essentials -#PINAKAMAKINANG “The Brilliant Concert 2023 is for processing! Get ready to witness the BRIGHTEST event of the year. Please find your ticket reference below. Your tickets are attached to this email. Please show a printed or mobile version of your tickets when you arrive at the venue, and you’ll be good to go!</span></font></p><p><font color="#555555"><span style="font-size: 14px;"><br></span></font></p><p><font color="#555555"><span style="font-size: 14px;">Date: 07 February 2023</span></font></p><p><font color="#555555"><span style="font-size: 14px;">Time: 4:00 PM</span></font></p><p><font color="#555555"><span style="font-size: 14px;"><br></span></font></p><p><font color="#555555"><span style="font-size: 14px;">Customer details:</span></font></p><p><font color="#555555"><span style="font-size: 14px;">Name: {FIRST_NAME} {LAST_NAME}</span></font></p><p><font color="#555555"><span style="font-size: 14px;">Phone: {PHONE_NUMBER}</span></font></p><p><span style="color: rgb(85, 85, 85); font-size: 14px;">Number of Seats: {TOTAL_SEATS}</span><font color="#555555"><span style="font-size: 14px;"><br></span></font></p><p><font color="#555555"><span style="font-size: 14px;">Remarks: {REMARKS}</span></font></p><p><font color="#555555"><span style="font-size: 14px;"><br></span></font></p><p><font color="#555555"><span style="font-size: 14px;">Event location:</span></font></p><p><font color="#555555"><b style="font-size: 14px;">Smart Araneta Coliseum</b><br><span style="font-size: 14px;">General Roxas Ave, Araneta City, QC, 1109 Metro Manila</span></font></p><p><font color="#555555"><span style="font-size: 14px;"><br></span></font></p><p><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><a href="https://goo.gl/maps/P7gXh8FEMLjPSxUH6" target="_blank" style="background-color: rgb(0, 179, 147); color: rgb(255, 255, 255); padding: 10px 15px;">Open on Google Map</a></span></p><div><br></div><p><font color="#555555"><span style="font-size: 14px;"><br></span></font></p><p><font color="#555555"><span style="font-size: 14px;">We can’t wait to see you!</span></font></p><p style=""><br></p>            <p style="color: rgb(85, 85, 85); font-size: 14px;">{SIGNATURE}</p>        </div>    </div></div>';

        $template_name = 'event_pass';
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
}
