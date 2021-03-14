<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Accounts extends Seeder
{
	public function run()
	{
		$data = [
			[
				'name' => 'Savings Acount',
				'number' => '159258654357',
				'remarks' => '',
			],
			[
				'name' => 'Sales Acount',
				'number' => '258456951357',
				'remarks' => '',
			],
			[
				'name' => 'Payroll Acount',
				'number' => '951357258456',
				'remarks' => '',
			],
			[
				'name' => 'Purchase Acount',
				'number' => '159357654456',
				'remarks' => '',
			]
		];
		$this->db->table('accounts')->ignore(true)->insertBatch($data);
	}
}
