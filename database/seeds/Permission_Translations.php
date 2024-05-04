<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Permission_Translations extends Seeder
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
                'permission_id'    => '42',
                'locale'           => 'ar',
                'name'             => 'أقسام الشبكات',
            ],
            [
                'permission_id'    => '42',
                'locale'           => 'en',
                'name'             => 'Network Sections',
            ]
        ];

        foreach ($entries as $entry) {
            DB::table('permission_translations')->insert($entry);
        }
	}
}
