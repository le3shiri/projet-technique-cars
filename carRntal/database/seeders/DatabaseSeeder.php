<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Models;
use App\Models\Car;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Import Users
        $this->command->info('Seed Users');
        $usersFile = base_path('database/seeders/data/users.csv');
        if (file_exists($usersFile)) {
            $data = $this->readCsv($usersFile);
            foreach ($data as $row) {
                User::updateOrCreate(
                    ['id' => $row['id']],
                    [
                        'name' => $row['name'],
                        'email' => $row['email'],
                        'password' => $row['password'], 
                        'role' => $row['role'],
                    ]
                );
            }
        }

        // 2. Import Models (Car Models)
        $this->command->info('Seed Models');
        $modelsFile = base_path('database/seeders/data/models.csv');
        $modelIds = [];
        if (file_exists($modelsFile)) {
            $data = $this->readCsv($modelsFile);
            foreach ($data as $row) {
                $model = Models::updateOrCreate(
                    ['id' => $row['id']],
                    [
                        'name' => $row['name'],
                        'brand' => $row['brand'],
                    ]
                );
                $modelIds[] = $model->id;
            }
        }

        // 3. Import Cars
        $this->command->info('Seed Cars');
        $carsFile = base_path('database/seeders/data/cars.csv');
        if (file_exists($carsFile)) {
            $data = $this->readCsv($carsFile);            
            if (empty($modelIds)) {
                $modelIds = Models::pluck('id')->toArray();
            }

            if (!empty($modelIds)) {
                foreach ($data as $row) {
                    Car::updateOrCreate(
                        ['id' => $row['id']],
                        [
                            'user_id' => $row['user_id'],
                            'model_id' => $row['model_id'],
                            'price_per_day' => $row['price_per_day'],
                            'status' => $row['status'],
                            'year' => $row['year'],
                            'image' => $row['image'],
                        ]
                    );
                }
            } else {
                $this->command->error('No Models found. Cannot seed Cars.');
            }
        }
    }
    private function readCsv($filename)
    {
        $data = [];
        if (($handle = fopen($filename, "r")) !== FALSE) {
            $header = fgetcsv($handle, 1000, ",");
            while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if (count($header) == count($row)) {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }
        return $data;
    }
}
