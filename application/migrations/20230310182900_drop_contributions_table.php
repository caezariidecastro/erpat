<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Drop_contributions_table extends CI_Migration { 
        
        public function up()
        {
                $this->dbforge->drop_table('contribution_entries', TRUE);
                $this->dbforge->drop_table('contribution_categories', TRUE);
        }

        public function down()
        {
                //Do nothing
        }
}