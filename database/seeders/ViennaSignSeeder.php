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
            'id' => 'A16A',
            'name' => 'Passagem de Peões',
            'image' => 'img/ViennaSigns/A16a.png',
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
        DB::table('vienna_signs')->insert([
            'id' => 'A2A',
            'name' => 'Lomba',
            'image' => 'img/ViennaSigns/A2A.png',
            'IDCategory' => 1,
        ]);
        DB::table('vienna_signs')->insert([
            'id' => 'A5',
            'name' => 'Pavimento',
            'image' => 'img/ViennaSigns/A5.png',
            'IDCategory' => 1,
        ]);
        DB::table('vienna_signs')->insert([
            'id' => 'A6',
            'name' => 'Projecção de gravilha',
            'image' => 'img/ViennaSigns/A6.png',
            'IDCategory' => 1,
        ]);
        DB::table('vienna_signs')->insert([
            'id' => 'A9',
            'name' => 'Queda de pedras',
            'image' => 'img/ViennaSigns/A9.png',
            'IDCategory' => 1,
        ]);
        DB::table('vienna_signs')->insert([
            'id' => 'A12',
            'name' => 'Vento lateral',
            'image' => 'img/ViennaSigns/A12.png',
            'IDCategory' => 1,
        ]);
        DB::table('vienna_signs')->insert([
            'id' => 'A14',
            'name' => 'Crianças',
            'image' => 'img/ViennaSigns/A14.png',
            'IDCategory' => 1,
        ]);
        DB::table('vienna_signs')->insert([
            'id' => 'A17',
            'name' => 'Saída de ciclistas',
            'image' => 'img/ViennaSigns/A17.png',
            'IDCategory' => 1,
        ]);
        DB::table('vienna_signs')->insert([
            'id' => 'A19A',
            'name' => 'Animais',
            'image' => 'img/ViennaSigns/A19A.png',
            'IDCategory' => 1,
        ]);
        DB::table('vienna_signs')->insert([
            'id' => 'A19B',
            'name' => 'Animais Selvagens',
            'image' => 'img/ViennaSigns/A19B.png',
            'IDCategory' => 1,
        ]);
        DB::table('vienna_signs')->insert([
            'id' => 'A21',
            'name' => 'Pista de aviação',
            'image' => 'img/ViennaSigns/A21.png',
            'IDCategory' => 1,
        ]);
        DB::table('vienna_signs')->insert([
            'id' => 'A22',
            'name' => 'Sinalização luminosa',
            'image' => 'img/ViennaSigns/A22.png',
            'IDCategory' => 1,
        ]);
        DB::table('vienna_signs')->insert([
            'id' => 'A23',
            'name' => 'Trabalhos na via',
            'image' => 'img/ViennaSigns/A23.png',
            'IDCategory' => 1,
        ]);
        DB::table('vienna_signs')->insert([
            'id' => 'A24',
            'name' => 'Cruzamento ou entroncamento',
            'image' => 'img/ViennaSigns/A24.png',
            'IDCategory' => 1,
        ]);
        DB::table('vienna_signs')->insert([
            'id' => 'A25',
            'name' => 'Trânsito nos dois sentidos',
            'image' => 'img/ViennaSigns/A25.png',
            'IDCategory' => 1,
        ]);
        DB::table('vienna_signs')->insert([
            'id' => 'A26',
            'name' => 'Passagem de nível com guarda',
            'image' => 'img/ViennaSigns/A26.png',
            'IDCategory' => 1,
        ]);
        DB::table('vienna_signs')->insert([
            'id' => 'A27',
            'name' => 'Passagem de nível sem guarda',
            'image' => 'img/ViennaSigns/A27.png',
            'IDCategory' => 1,
        ]);
        DB::table('vienna_signs')->insert([
            'id' => 'A28',
            'name' => 'Intersecção com via onde circulam veículos sobre carris',
            'image' => 'img/ViennaSigns/A28.png',
            'IDCategory' => 1,
        ]);
        DB::table('vienna_signs')->insert([
            'id' => 'A29',
            'name' => 'Outros perigos',
            'image' => 'img/ViennaSigns/A29.png',
            'IDCategory' => 1,
        ]);
        DB::table('vienna_signs')->insert([
            'id' => 'A30',
            'name' => 'Congestionamento',
            'image' => 'img/ViennaSigns/A30.png',
            'IDCategory' => 1,
        ]);    
    }
}
