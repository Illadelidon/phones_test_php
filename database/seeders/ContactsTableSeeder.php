<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class ContactsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 15; $i++) {
            // Створюємо контакт
            $contactId = DB::table('contacts')->insertGetId([
                'first_name' => $faker->unique()->firstName(),
                'last_name' => $faker->unique()->lastName(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);


            $numberOfPhones = rand(1, 3);

            for ($j = 0; $j < $numberOfPhones; $j++) {
                DB::table('phone_numbers')->insert([
                    'contact_id' => $contactId, // Foreign key для зв'язку
                    'phone_number' => $faker->unique()->phoneNumber(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
