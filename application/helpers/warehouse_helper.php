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

if( !function_exists("get_rack_name") ) {
    function get_rack_name( $id = 0 ) {
        $ci = get_instance();
        $ci->load->model('Racks_model');
        if($current = $ci->Racks_model->get_one($id)) {
            return get_id_name($current->id, 'R');
        }

        return;
        
    }
}

if( !function_exists("get_bay_name") ) {
    function get_bay_name( $id = 0 ) {
        $ci = get_instance();
        $ci->load->model('Bays_model');
        if($current = $ci->Bays_model->get_one($id)) {
            return get_id_name($current->id, 'B');
        }

        return;
        
    }
}