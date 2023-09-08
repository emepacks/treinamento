<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Address;
use App\Models\Company;
use App\Models\User;
use App\Models\UserCompanies;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Address::factory(10)->create();
        Company::factory(10)->create();
        User::factory(10)->create();
        UserCompanies::factory(10)->create();
    }
}
