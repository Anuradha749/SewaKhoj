<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\ServiceProvider;
use Illuminate\Database\Seeder;

class ServiceProviderSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(CategorySeeder::class);

        $providers = [
            [
                'name' => 'Ram Sharma',
                'phone' => '9800000001',
                'email' => 'ram@example.com',
                'address' => 'Itahari, Sunsari',
                'bio' => 'Experienced plumber providing home plumbing services.',
                'experience_years' => 5,
                'category' => 'Plumber',
            ],
            [
                'name' => 'Sita Rai',
                'phone' => '9800000002',
                'email' => 'sita@example.com',
                'address' => 'Dharan, Sunsari',
                'bio' => 'Electrical installation and repair services.',
                'experience_years' => 4,
                'category' => 'Electrician',
            ],
            [
                'name' => 'Hari Thapa',
                'phone' => '9800000003',
                'email' => 'hari@example.com',
                'address' => 'Biratnagar, Morang',
                'bio' => 'Home and office cleaning services.',
                'experience_years' => 3,
                'category' => 'Cleaner',
            ],
            [
                'name' => 'Mina Limbu',
                'phone' => '9800000004',
                'email' => 'mina@example.com',
                'address' => 'Itahari, Sunsari',
                'bio' => 'Furniture making and woodwork services.',
                'experience_years' => 6,
                'category' => 'Carpenter',
            ],
            [
                'name' => 'Anil Karki',
                'phone' => '9800000005',
                'email' => 'anil@example.com',
                'address' => 'Dharan, Sunsari',
                'bio' => 'Private tutoring for school students.',
                'experience_years' => 3,
                'category' => 'Tutor',
            ],
            [
                'name' => 'Bikash Gurung',
                'phone' => '9800000006',
                'email' => 'bikash@example.com',
                'address' => 'Itahari, Sunsari',
                'bio' => 'Vehicle repair and maintenance services.',
                'experience_years' => 7,
                'category' => 'Mechanic',
            ],
        ];

        foreach ($providers as $provider) {
            $category = Category::where(
                'name',
                $provider['category']
            )->firstOrFail();

            ServiceProvider::firstOrCreate(
                ['phone' => $provider['phone']],
                [
                    'name' => $provider['name'],
                    'email' => $provider['email'],
                    'address' => $provider['address'],
                    'bio' => $provider['bio'],
                    'experience_years' => $provider['experience_years'],
                    'category_id' => $category->id,
                    'is_verified' => false,
                    'is_active' => true,
                ]
            );
        }
    }
}
