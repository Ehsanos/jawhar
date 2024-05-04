<?php

use Database\Seeders\Permissions;
use Database\Seeders\Permission_Translations;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(Permissions::class);
        $this->call(Permission_Translations::class);
    }
}
