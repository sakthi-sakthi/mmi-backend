<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(FileSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(OptionSeeder::class);

        User::create([
            'email' => 'mmisuperiorgeneral@gmail.com',
            'password' => Hash::make('mmisuperiorgeneral@gmail.com'),
            'role' => 'admin',
            'name' => 'Admin'
        ]);
    }
}
