<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EntitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('entities')->insert([
            'name' => 'Youphones',
            'logo' => 'img/Entities/youphones.jpg',
            'address' => 'Rua Principal, 1',
            'phone' => 262789054,
        ]);
    }
}