<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SignsGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('signs_deploy_groups')->insert([
            'IDIviSign' => 1,
            'IDDeployGroup' => 1,
        ]);

        DB::table('signs_deploy_groups')->insert([
            'IDIviSign' => 2,
            'IDDeployGroup' => 1,
        ]);

        DB::table('signs_deploy_groups')->insert([
            'IDIviSign' => 4,
            'IDDeployGroup' => 2,
        ]);

        DB::table('signs_deploy_groups')->insert([
            'IDIviSign' => 5,
            'IDDeployGroup' => 2,
        ]);
    }
}
