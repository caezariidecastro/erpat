<?php

class Settings_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = $this->db->dbprefix('settings');
        parent::__construct($this->table);
    }

    function get_setting($setting_name, $type = "") {
        $options = array('setting_name' => $setting_name);
        if(!empty($type)) {
            $options['type'] = $type;
        }
        $result = $this->db->get_where($this->table, $options, 1);

        if ($result->num_rows() == 1) {
            return $result->row()->setting_value;
        }
    }

    function get_group_setting($options) {
        $setting_table = $this->table;

        $where = "";
        $type = get_array_value($options, "type");
        if ($type) {
            $where .= " AND $setting_table.type='$type'";
        }
        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $setting_table.setting_name LIKE '%user_{$user_id}_%'";
        }

        $sql = "SELECT $setting_table.*
        FROM $setting_table
        WHERE $setting_table.deleted=0 $where";
        return $this->db->query($sql)->result();
    }

    function save_setting($setting_name, $setting_value, $type = false) {
        $fields = array(
            'setting_name' => $setting_name,
            'setting_value' => $setting_value,
        );

        if( $type  ) {
            $fields['type'] = $type;
        }

        $exists = $this->get_setting($setting_name);
        if ($exists === NULL) {
            return $this->db->insert($this->table, $fields);
        } else {
            $this->db->where('setting_name', $setting_name);
            return $this->db->update($this->table, $fields);
        }
    }

    //find all app settings and login user's setting
    //user's settings are saved like this: user_[userId]_settings_name;
    function get_all_required_settings($user_id = 0) {
        $settings_table = $this->table;
        $sql = "SELECT $settings_table.setting_name,  $settings_table.setting_value
        FROM $settings_table
        WHERE $settings_table.deleted=0 AND ($settings_table.type != 'user' OR ($settings_table.type ='user' AND $settings_table.setting_name LIKE 'user_" . $user_id . "_%'))";
        return $this->db->query($sql);
    }

}
