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
        DB::table('user_info')->insert([
            [
                'user_id' => 1,
                'address' => 'ha noi',
                'describe' => 'ba vi',
            ],
            [
                'user_id' => 2,
                'address' => 'thuy phuong ha noi',
                'describe' => 'ba vi',
            ],
            [
                'user_id' => 3,
                'address' => 'thuy phuong ha noi',
                'describe' => 'ba vi',
            ],
            [
                'user_id' => 4,
                'address' => 'ha noi',
                'describe' => 'ba vi',
            ],
            [
                'user_id' => 5,
                'address' => 'thuy phuong ha noi',
                'describe' => 'ba vi',
            ],
            [
                'user_id' => 6,
                'address' => 'thuy phuong ha noi',
                'describe' => 'ba vi',
            ],
        ]);
    }
}
