<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_users_add_usertype extends CI_Migration { 
        
        public function up()
        {
                $this->db->query("ALTER TABLE `users` CHANGE `user_type` `user_type` ENUM('staff','client','lead','customer','driver','supplier','vendor','system') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'client';");
        }

        public function down()
        {
                $this->db->query("ALTER TABLE `users` CHANGE `user_type` `user_type` ENUM('staff','client','lead','customer','driver','supplier','vendor') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'client';");
        }
}