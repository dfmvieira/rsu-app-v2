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
            'name' => 'teste1',
            'GUID' => 'c927d732-a295-480d-9ccb-c0b7c5ec6554',
            'viennaID' => 1,
            'latitude' => '39.733986',
            'longitude' => '-8.821822',
            'status' => true,
            'comment' => 'testestestesteste',
            /* 'locationID' => 1,
            'envelopeIVI' => 1,
            'IVIMID' => 1 */
        ]);
        DB::table('ivi_signs_map')->insert([
            'id' => 2,
            'name' => 'teste2',
            'GUID' => 'c927d732-a295-480d-9ccb-c0b7c5ec6554',
            'viennaID' => 1,
            'latitude' => '39.734152',
            'longitude' => '-8.821918',
            'status' => 1,
            'comment' => 'testestestesteste',
            /* 'locationID' => 1,
            'envelopeIVI' => 1,
            'IVIMID' => 1 */
        ]);
        DB::table('ivi_signs_map')->insert([
            'id' => 3,
            'name' => 'teste1',
            'GUID' => 'c927d732-a295-480d-9ccb-c0b7c5ec6554',
            'viennaID' => 1,
            'latitude' => '39.734318',
            'longitude' => '-8.821511',
            'status' => 1,
            'comment' => 'testestestesteste',
           /*  'locationID' => 1,
            'envelopeIVI' => 1,
            'IVIMID' => 1 */
        ]);
    }
}
