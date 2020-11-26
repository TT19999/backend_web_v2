<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permission_role')->insert([
            'permission_id' => '7',
            'role_id' => '1'
        ]);
    }
}
