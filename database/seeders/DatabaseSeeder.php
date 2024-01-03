<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(UnitSeeder::class);
        $this->call(TermsSeeder::class);
        $this->call(ItemTypeSeeder::class);
        $this->call(DeliveriesSeeder::class);
        $this->call(ColorSeeder::class);
        $this->call(SizeSeeder::class);
        $this->call(DocumentNumberSeeder::class);
        $this->call(EmailSeeder::class);
    }
}
