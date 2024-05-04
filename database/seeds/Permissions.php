<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Permissions extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$entries = [

			[
				'roleSlug'    => 'NetworkSections',
			]
		];
		
		foreach ($entries as $entry) {
			DB::table('permissions')->insert($entry);
		}
	}
}
