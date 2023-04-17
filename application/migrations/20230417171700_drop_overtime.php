<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Drop_overtime extends CI_Migration { 
        
        public function up()
        {
                $this->dbforge->drop_table('overtime', TRUE);
        }

        public function down()
        {
                //Do nothing.
        }
}