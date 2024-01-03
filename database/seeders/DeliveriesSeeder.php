<?php

namespace Database\Seeders;

use App\Models\DeliveryMethod;
use Illuminate\Database\Seeder;

class DeliveriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DeliveryMethod::create(['name'=>'Mail']);
        DeliveryMethod::create(['name'=>'DHL']);
        DeliveryMethod::create(['name'=>'FEDEX']);
    }
}
