<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Colors;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Colors::create(['description' => 'Black']);
        Colors::create(['description' => 'Blue']);
        Colors::create(['description' => 'Gray']);
        Colors::create(['description' => 'Orange']);
        Colors::create(['description' => 'Purple']);
        Colors::create(['description' => 'Red']);
        Colors::create(['description' => 'White']);
        Colors::create(['description' => 'Yellow']);
    }
}
