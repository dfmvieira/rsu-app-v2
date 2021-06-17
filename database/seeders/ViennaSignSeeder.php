<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ViennaSignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('vienna_signs')->insert([
            'id' => 'A16a',
            'name' => 'Passagem de Peões',
            'image' => 'img/ViennaSigns/A16a.jpg',
            'IDCategory' => 1,
        ]);
        DB::table('vienna_signs')->insert([
            'id' => 'B1',
            'name' => 'Cedência de passagem',
            'image' => 'img/ViennaSigns/B1.png',
            'IDCategory' => 2,
        ]);
        DB::table('vienna_signs')->insert([
            'id' => 'H7',
            'name' => 'Passagem de Peões',
            'image' => 'img/ViennaSigns/H7.png',
            'IDCategory' => 6,
        ]);
    }
}
