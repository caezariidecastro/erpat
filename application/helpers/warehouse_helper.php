<?php

if( !function_exists("get_warehouse_name") ) {
    function get_warehouse_name( $id = 0 ) {
        $ci = get_instance();
        $ci->load->model('Warehouse_model');
        if($current = $ci->Warehouse_model->get_one($id)) {
            return $current->name;
        }

        return;
        
    }
}