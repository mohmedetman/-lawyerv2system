<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $faker = Faker::create();
        $batchSize = 1000; // Insert 1000 records at a time
        $totalRecords = 1000000; // 1 million records

        for ($i = 0; $i < $totalRecords; $i += $batchSize) {
            $data = [];

            for ($j = 0; $j < $batchSize; $j++) {
                $data[] = [
                    'name' => $faker->name,
                    'email' => $faker->unique()->safeEmail,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            DB::table('tests')->insert($data);

            echo "Inserted: " . ($i + $batchSize) . " records\n";
        }
    }
}
