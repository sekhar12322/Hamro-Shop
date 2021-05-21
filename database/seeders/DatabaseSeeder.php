<?php

namespace Database\Seeders;

use App\Models\Admin;
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
        Admin::insert([
           'name' => "sekhar khanal",
            'email' => "khanalsekhar13@gmail.com",
            'address' => "sankhamool",
            'image' => "",
            'password' => bcrypt("password"),
            'phone' => "9860848510",
            'role_id' => 1,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
