<?php

namespace Database\Seeders;

use App\Models\CustomizeMail;
use Illuminate\Database\Seeder;

class EmailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CustomizeMail::create([
            'type' => 'Stage',
            'subject' => 'Stage Information',
            'message' => 'Thank you for trusting us, a new progress has been made in the project, the details can be viewed in the application.

            Best regards.'
        ]);

        CustomizeMail::create([
            'type' => 'Estimate',
            'subject' => 'Estimate Information',
            'message' => 'Thank you for trusting us, we send attached the detail of your estimate.
            
            Best regards.'
        ]);

        CustomizeMail::create([
            'type' => 'Invoice',
            'subject' => 'Invoice Information',
            'message' => 'Thank you for trusting us, we send attached the detail of your invoice.
            
            Best regards.'
        ]);

        CustomizeMail::create([
            'type' => 'Payment',
            'subject' => 'Payment Information',
            'message' => 'Thank you for trusting us, we send attached the detail of your payment.
            
            Best regards.'
        ]);
    }
}
