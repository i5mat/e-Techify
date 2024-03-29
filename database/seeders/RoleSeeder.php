<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Role::factory()->times(10)->create();

        DB::table('roles')->insert([
            'name' => 'Reseller'
        ]);

        DB::table('roles')->insert([
            'name' => 'Distributor'
        ]);

        DB::table('roles')->insert([
            'name' => 'User'
        ]);
    }
}
