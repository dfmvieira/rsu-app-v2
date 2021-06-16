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
            'category' => 'A: Danger warning'
        ]);
        DB::table('vienna_sign_categories')->insert([
            'category' => 'B: Priority warning'
        ]);
        DB::table('vienna_sign_categories')->insert([
            'category' => 'C: Prohibitory or restrictive warning'
        ]);
        DB::table('vienna_sign_categories')->insert([
            'category' => 'D: Mandatory'
        ]);
        DB::table('vienna_sign_categories')->insert([
            'category' => 'E: Special regulation'
        ]);
        DB::table('vienna_sign_categories')->insert([
            'category' => 'F: Information, facilities, or service'
        ]);
        DB::table('vienna_sign_categories')->insert([
            'category' => 'G: Direction, position, or indication'
        ]);
        DB::table('vienna_sign_categories')->insert([
            'category' => 'H: Additional panels'
        ]);
    }
}
