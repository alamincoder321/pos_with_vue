<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
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
        User::create([
            'name'              => "Admin",
            'username'          => "admin",
            'email'             => "admin@gmail.com",
            'email_verified_at' => now(),
            'password'          => '$2a$04$Up80bDycufY/qovhzp2Zf./rU0CXScq22KcjT0WlB4UIRLEzSdXPi',   // password
            'remember_token'    => Str::random(1),
        ]);
        // \App\Models\User::factory(50)->create();
        $this->call(PermissionSeeder::class);
        $this->call(CompanyProfileSeeder::class);
    }
}
