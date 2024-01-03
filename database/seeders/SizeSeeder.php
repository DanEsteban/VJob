<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sizes;


class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Sizes::create(['description' => 'Small']);
        Sizes::create(['description' => 'Medium']);
        Sizes::create(['description' => 'Large']);
        Sizes::create(['description' => 'Xtra Large']);
    }
}
