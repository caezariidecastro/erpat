<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Accounts extends Migration
{
	public function up()
	{
		$this->forge->addField([
			'id' => [
				'type' => 'BIGINT',
				'constraint' => 20,
				'auto_increment' => true,
			],
			'name' => [
				'type' => 'TEXT',
				'null' => TRUE,
			],
			'number' => [
				'type' => 'TEXT',
				'null' => TRUE,
			],
			'remarks' => [
				'type' => 'TEXT',
				'null' => TRUE,
			],
			'date_created datetime default current_timestamp',
			'created_by' => [
				'type' => 'BIGINT',
				'constraint' => '20',
				'default' => '0',
			],
			'last_update datetime default current_timestamp on update current_timestamp',
			'deleted' => [
				'type' => 'TINYINT',
				'constraint' => '1',
				'default' => '0',
			],
		]);

		$this->forge->addKey('id', true);
		$this->forge->createTable('accounts', true);
	}

	public function down()
	{
		$this->forge->dropTable('accounts');
	}
}
