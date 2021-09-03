<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeployGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('deploy_groups')->insert([
            'name' => 'deploy ESTG',
            'notes' => '',
            'entityID' => 1,
            'deployed' => 0,
        ]);

        DB::table('deploy_groups')->insert([
            'name' => 'deploy Marquês de Pombal',
            'notes' => '',
            'entityID' => 1,
            'deployed' => 0,
        ]);
    }
}
