<?php

namespace Database\Seeders;

use App\Models\ItemTypes;
use Illuminate\Database\Seeder;

class ItemTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ItemTypes::create(['name'=>'Service']);
        ItemTypes::create(['name'=>'Inventory Part']);
        ItemTypes::create(['name'=>'Non-inventory Part']);
    }
}
