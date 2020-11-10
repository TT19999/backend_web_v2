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
        DB::table('permissions')->insert([
            [
                'name' => 'view_trip',
            ],
            [
                'name' => 'edit_trip',
            ],
            [
                'name' => 'create_trip',
            ],
            [
                'name' => 'delete_trip',
            ],
        ]);
    }
}
