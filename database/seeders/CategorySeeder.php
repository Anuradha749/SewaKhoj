<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Plumber',
                'description' => 'Pipe repairs and water system services.',
                'icon' => 'plumber',
            ],
            [
                'name' => 'Electrician',
                'description' => 'Electrical repairs and installations.',
                'icon' => 'electrician',
            ],
            [
                'name' => 'Cleaner',
                'description' => 'Home and office cleaning services.',
                'icon' => 'cleaner',
            ],
            [
                'name' => 'Carpenter',
                'description' => 'Furniture repairs and woodwork.',
                'icon' => 'carpenter',
            ],
            [
                'name' => 'Tutor',
                'description' => 'Private tutoring and educational support.',
                'icon' => 'tutor',
            ],
            [
                'name' => 'Mechanic',
                'description' => 'Vehicle repairs and maintenance.',
                'icon' => 'mechanic',
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                [
                    'description' => $category['description'],
                    'icon' => $category['icon'],
                    'is_active' => true,
                ]
            );
        }
    }
}
