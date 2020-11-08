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
            ['name' => 'view_info'],
            ['name' => 'update_info'],
            ['name' => 'delete_info'],
            ['name' => 'restore_info'],
            ['name' => 'view_trip'],
            ['name' => 'update_trip'],
            ['name' => 'delete_trip'],
            ['name' => 'restore_trip'],
            ['name' => 'create_trip'],
        ]);
        
        DB::table('roles')->insert([
            ['name' => 'admin'],
            ['name' => 'user'],
            ['name' => 'contributor'],
        ]);
        DB::table('role_user')->insert([
            'role_id' => 2,
            'user_id' => 1,
        ]);
        DB::table('role_user')->insert([
            'role_id' => 3,
            'user_id' => 2,
        ]);
        DB::table('permission_role')->insert([
            ['permission_id' => 1, 'role_id' => 1],
            ['permission_id' => 2, 'role_id' => 1],
            ['permission_id' => 3, 'role_id' => 1],
            ['permission_id' => 4, 'role_id' => 1],
        ]);
        DB::table('permission_role')->insert([
            ['permission_id' => 1, 'role_id' => 2],
            ['permission_id' => 2, 'role_id' => 2],
            ['permission_id' => 4, 'role_id' => 2],
        ]);
        DB::table('permission_role')->insert([
            ['permission_id' => 9, 'role_id' => 3],
            ['permission_id' => 1, 'role_id' => 3],
            ['permission_id' => 2, 'role_id' => 3],
            ['permission_id' => 4, 'role_id' => 3],
        ]);
    }
}
