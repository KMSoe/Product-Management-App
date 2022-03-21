<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Electrical Vehicle/e-Power',
                'description' => Str::words(50),
            ],
            [
                'name' => 'Compact Car',
                'description' => Str::words(50),
            ],
            [
                'name' => 'Light Car',
                'description' => Str::words(50),
            ],
            [
                'name' => 'Minivan',
                'description' => Str::words(50),
            ],
            [
                'name' => 'Sports & Specialty',
                'description' => Str::words(50),
            ],
            [
                'name' => 'Sedan',
                'description' => Str::words(50),
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
