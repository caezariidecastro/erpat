<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Drop_incentives_table extends CI_Migration { 
        
        public function up()
        {
                $this->dbforge->drop_table('incentive_entries', TRUE);
                $this->dbforge->drop_table('incentive_categories', TRUE);
        }

        public function down()
        {
                //Do nothing
        }
}