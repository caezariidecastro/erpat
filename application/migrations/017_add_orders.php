<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Add_orders extends CI_Migration { 
        
        public function up()
        {
                $fields = array(
                        'id' => array(
                                'type' => 'INT',
                                'constraint' => 11,
                                'unsigned' => TRUE,
                                'auto_increment' => TRUE
                        ),
                        'uuid' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 36,
                        ),

                        'customer_id' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 36,
                                'default' => '',
                                'null' => true
                        ),
                        'store_id' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 36,
                                'default' => '',
                                'null' => true
                        ),
                        'driver_id' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 36,
                                'default' => '',
                                'null' => true
                        ),
                        'address_id' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 36,
                                'default' => '',
                                'null' => true
                        ),


                        'orders' => array(
                                'type' => 'longtext',
                                'default' => '',
                                'null' => true
                        ),
                        'chats' => array(
                                'type' => 'mediumtext',
                                'default' => '',
                                'null' => true
                        ),
                        'notes' => array(
                                'type' => 'text',
                                'default' => '',
                                'null' => true
                        ),

                        'offers_id' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 36,
                                'default' => '',
                                'null' => true
                        ),
                        'pay_method_id' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 36,
                                'default' => '',
                                'null' => true
                        ),

                        'subtotal' => array(
                                'type' => 'decimal',
                                'constraint' => '20,2',
                                'default' => '0.00',
                        ),
                        'tax' => array(
                                'type' => 'decimal',
                                'constraint' => '20,2',
                                'default' => '0.00',
                        ),
                        'discount' => array(
                                'type' => 'decimal',
                                'constraint' => '20,2',
                                'default' => '0.00',
                        ),
                        'delivery_fee' => array(
                                'type' => 'decimal',
                                'constraint' => '20,2',
                                'default' => '0.00',
                        ),

                        'status' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 50,
                                'default' => 'pending',
                        ),

                        'created_by' => array(
                            'type' => 'INT',
                            'constraint' => 11,
                            'default' => '0',
                            'null' => true
                        ),
                        'created_at datetime default current_timestamp',
                        'updated_at datetime default current_timestamp on update current_timestamp',
                        'active' => array(
                                'type' => 'TINYINT',
                                'constraint' => 1,
                                'default' => '1',
                        ),
                        'deleted' => array(
                            'type' => 'TINYINT',
                            'constraint' => 1,
                            'default' => '0',
                        ),
                    );
                    $this->dbforge->add_field($fields);
                    $this->dbforge->add_key('id', TRUE);
                    $this->dbforge->add_key('uuid');

                    $result = $this->dbforge->create_table('orders', TRUE); //IF NOT EXIST.
        }

        public function down()
        {
                $this->dbforge->drop_table('orders',TRUE); //DROP TABLE IF EXISTS table_name
        }
}