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
            'name' => 'Camera Municipal Leiria',
            'logo' => 'img/Entities/cmleiria.jpg',
            'address' => 'Rua principal 2',
            'phone' => 123456789,
        ]);

        DB::table('entities')->insert([
            'name' => 'Camera Municipal Porto',
            'logo' => 'img/Entities/cmporto.jpg',
            'address' => 'Rua Principal, 1',
            'phone' => 987654321,
        ]);
    }
}
