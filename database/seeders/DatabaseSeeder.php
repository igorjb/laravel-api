<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Iluminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        //Model::unguard();

        $this->call(CompaniesSeed::class);
        $this->call(JobsSeed::class);

        //Model::reguard();
    }
}
