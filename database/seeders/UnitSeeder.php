<?php

namespace Database\Seeders;

use App\Models\UnitMeasure;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UnitMeasure::create(['abbreviation'=>'u', 'description'=>'Unit']);
        UnitMeasure::create(['abbreviation'=>'ea', 'description'=>'Each']);
        UnitMeasure::create(['abbreviation'=>'pr', 'description'=>'Pair']);
        UnitMeasure::create(['abbreviation'=>'bx', 'description'=>'Box']);
    }
}
