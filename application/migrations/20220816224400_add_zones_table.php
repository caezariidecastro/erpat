<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Add_zones_table extends CI_Migration { 
        
        public function up()
        {
            $fields = array(
                'id' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => TRUE,
                        'auto_increment' => TRUE
                ),
                "`warehouse_id` int(11) DEFAULT NULL",
                'qrcode' => array(
                    'type' => 'varchar',
                    'constraint' => 100,
                ),
                'barcode' => array(
                    'type' => 'varchar',
                    'constraint' => 50,
                ),
                'rfid' => array(
                    'type' => 'varchar',
                    'constraint' => 25,
                ),
                'labels' => array(
                    'type' => 'text'
                ),
                "`remarks` text NOT NULL",
                "`status` enum('inactive','active') NOT NULL DEFAULT 'inactive'",
                "`created_by` int(11) DEFAULT NULL",
                "`timestamp` timestamp NULL DEFAULT NULL",
                '`updated_at` timestamp default current_timestamp on update current_timestamp',
                'deleted' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => '0',
                ),
            );
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->add_key('qrcode');
            $this->dbforge->add_key('barcode');
            $this->dbforge->add_key('rfid');

            $result = $this->dbforge->create_table('zones', TRUE); //IF NOT EXIST.

            if(!$result) {
                throw new Exception("Migration_Add_zones_table error");
            }
            
        }

        public function down()
        {
            $this->dbforge->drop_table('zones',TRUE); //DROP TABLE IF EXISTS table_name
        }
}