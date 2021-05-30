<?php 

defined('BASEPATH') OR exit('No direct script access allowed');
                        
class Orders_model extends Crud_Model 
{
    private $table = null;

    function __construct() {
        $this->table = 'orders';
        parent::__construct($this->table);
    }      
    
    function get_details($options = array()) {
        $orders_table = $this->db->dbprefix('orders');
        $users_table = $this->db->dbprefix('users');

        $where = "";
        
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $orders_table.uuid=$id";
        }

        $sql = "SELECT $orders_table.*, TRIM(CONCAT($users_table.first_name, ' ', $users_table.last_name)) AS creator
        FROM $orders_table 
        LEFT JOIN $users_table ON $users_table.id = $orders_table.customer_id 
        WHERE $orders_table.active=1 AND $orders_table.deleted=0 $where";
     
        return $this->db->query($sql);
    }
                        
}
                        
/* End of file Orders_model.php */
    
                        