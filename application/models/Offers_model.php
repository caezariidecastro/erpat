<?php 

defined('BASEPATH') OR exit('No direct script access allowed');
                        
class Offers_model extends Crud_Model 
{
    private $table = null;

    function __construct() {
        $this->table = 'offers';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $offers_table = $this->db->dbprefix('offers');
        $users_table = $this->db->dbprefix('users');

        $where = "";
        
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $offers_table.uuid=$id";
        }

        $sql = "SELECT $offers_table.*, TRIM(CONCAT($users_table.first_name, ' ', $users_table.last_name)) AS creator
        FROM $offers_table 
        LEFT JOIN $users_table ON $users_table.id = $offers_table.created_by 
        WHERE $offers_table.active=1 AND $offers_table.deleted=0 $where";
     
        return $this->db->query($sql);
    }

    function get_label_suggestions($user_id) {
        $offers_table = $this->db->dbprefix('offers');
        $sql = "SELECT GROUP_CONCAT(labels) as label_groups
        FROM $offers_table
        WHERE $offers_table.deleted=0 AND $offers_table.created_by=$user_id";
        return $this->db->query($sql)->row()->label_groups;
    }                     
                        
}
                        
/* End of file Offers_model.php */
    
                        