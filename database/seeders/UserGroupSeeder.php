<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users_deploy_groups')->insert([
            'IDUser' => 8,
            'IDDeployGroup' => 1,
        ]);

        DB::table('users_deploy_groups')->insert([
            'IDUser' => 9,
            'IDDeployGroup' => 1,
        ]);

        DB::table('users_deploy_groups')->insert([
            'IDUser' => 10,
            'IDDeployGroup' => 2,
        ]);

        DB::table('users_deploy_groups')->insert([
            'IDUser' => 11,
            'IDDeployGroup' => 2,
        ]);

    }
}
