<?php

namespace Database\Seeders;

use App\Models\DocumentNumbers;
use Illuminate\Database\Seeder;
use League\CommonMark\Node\Block\Document;

class DocumentNumberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DocumentNumbers::create([
            'type'=>'Orders',
            'number' => 1
        ]);

        DocumentNumbers::create([
            'type'=>'Invoices',
            'number' => 1
        ]);
    }
}
