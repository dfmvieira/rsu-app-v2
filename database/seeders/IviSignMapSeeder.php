<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class IviSignMapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ivi_signs_map')->insert([
            'id' => 1,
            'entityId' => 1,
            'name' => 'Passadeira',
            'GUID' => 'c927d732-a295-480d-9ccb-c0b7c5ec6554',
            'viennaSignId' => 'H7',
            'latitude' => '39.733986',
            'longitude' => '-8.821822',
            'locked' => 0,
            'IDDetection' => 0,
            'IDAwareness' => 0,
            'IDRelevance' => 0,
            'deployed' => 0,
            'madeByFactory' => 0,
            'comment' => 'passadeira refeitorio',
            'published' => 0,
            'IDRSU' => 0,
        ]);
        DB::table('ivi_signs_map')->insert([
            'id' => 2,
            'entityId' => 1,
            'name' => 'Cedencia de Passagem',
            'GUID' => 'c927d732-a295-480d-9ccb-c0b7c5ec6554',
            'viennaSignId' => 'B1',
            'latitude' => '39.734152',
            'longitude' => '-8.821918',
            'locked' => 0,
            'IDDetection' => 0,
            'IDAwareness' => 0,
            'IDRelevance' => 0,
            'deployed' => 0,
            'madeByFactory' => 0,
            'comment' => 'cedencia de passagem',
            'published' => 0,
            'IDRSU' => 0,
        ]);
        DB::table('ivi_signs_map')->insert([
            'id' => 3,
            'entityId' => 1,
            'name' => 'Passadeira secundaria',
            'GUID' => 'c927d732-a295-480d-9ccb-c0b7c5ec6554',
            'viennaSignId' => 'H7',
            'latitude' => '39.734318',
            'longitude' => '-8.821511',
            'locked' => 0,
            'IDDetection' => 0,
            'IDAwareness' => 0,
            'IDRelevance' => 0,
            'deployed' => 1,
            'madeByFactory' => 0,
            'comment' => 'passadeira serviços academicos',
            'published' => 0,
            'IDRSU' => 1,
        ]);
        DB::table('ivi_signs_map')->insert([
            'id' => 4,
            'entityId' => 1,
            'name' => 'Passadeira',
            'GUID' => 'c927d732-a295-480d-9ccb-c0b7c5ec6554',
            'viennaSignId' => 'H7',
            'latitude' => '39.7395724358570',
            'longitude' => '-8.8072336014137',
            'locked' => 0,
            'IDDetection' => 0,
            'IDAwareness' => 0,
            'IDRelevance' => 0,
            'deployed' => 0,
            'madeByFactory' => 0,
            'comment' => 'passadeira',
            'published' => 1,
            'IDRSU' => 1,
        ]);
        DB::table('ivi_signs_map')->insert([
            'id' => 5,
            'entityId' => 1,
            'name' => 'Cedencia de Passagem',
            'GUID' => 'c927d732-a295-480d-9ccb-c0b7c5ec6554',
            'viennaSignId' => 'B1',
            'latitude' => '39.7396281235130',
            'longitude' => '-8.8082139488087',
            'locked' => 0,
            'IDDetection' => 0,
            'IDAwareness' => 0,
            'IDRelevance' => 0,
            'deployed' => 0,
            'madeByFactory' => 0,
            'comment' => 'Cedencia de Passagem',
            'published' => 1,
            'IDRSU' => 0    ,
        ]);
    }
}

           