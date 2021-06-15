<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ViennaSignCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('vienna_sign_categories')->insert([
            'name' => 'A: Danger warning'
        ]);
        DB::table('vienna_sign_categories')->insert([
            'name' => 'B: Priority warning'
        ]);
        DB::table('vienna_sign_categories')->insert([
            'name' => 'C: Prohibitory or restrictive warning'
        ]);
        DB::table('vienna_sign_categories')->insert([
            'name' => 'D: Mandatory'
        ]);
        DB::table('vienna_sign_categories')->insert([
            'name' => 'E: Special regulation'
        ]);
        DB::table('vienna_sign_categories')->insert([
            'name' => 'F: Information, facilities, or service'
        ]);
        DB::table('vienna_sign_categories')->insert([
            'name' => 'G: Direction, position, or indication'
        ]);
        DB::table('vienna_sign_categories')->insert([
            'name' => 'H: Additional panels'
        ]);
    }
}
