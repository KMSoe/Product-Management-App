<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
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
        $roles = [
            ["name" => "user", "value" => 1],
            ["name" => "admin", "value" => 2],
        ];
        foreach ($roles as $role) {
            Role::create($role);
        }

        User::create([
            "name" => "Kaung Myat Soe",
            "email" => "kaungmyatsoe.m192@gmail.com",
            "password" => bcrypt("111111"),
            "role_id" => 2
        ]);
        User::create([
            "name" => "Alice",
            "email" => "alice@gmail.com",
            "password" => bcrypt("111111"),
        ]);
        // \App\Models\User::factory(10)->create();
        $this->call(CategorySeeder::class);


    }
}
