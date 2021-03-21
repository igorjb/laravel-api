<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company; 
use Illuminate\Support\Str;

class CompaniesSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::create([
            'name' => Str::random(10),
            'email' => Str::random(10).'@gmail.com',
            'password' => bcrypt('secret'),
            'website' => 'http://www.google.com',
            'logo' => 'logo.png',
        ]);
    }
}
