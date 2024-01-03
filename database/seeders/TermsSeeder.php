<?php

namespace Database\Seeders;

use App\Models\PaymentTerms;
use Illuminate\Database\Seeder;

class TermsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentTerms::create(['name'=>'Cash']);
        PaymentTerms::create(['name'=>'Check']);
        PaymentTerms::create(['name'=>'Credit Card']);
        PaymentTerms::create(['name'=>'Transfer']);
    }
}
