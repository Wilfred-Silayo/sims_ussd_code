<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * note: to run Adminseeder use the following command ==>{ php artisan db:seed --class=AdminSeeder }
     */
   public function run()
    {
        Admin::factory()->create([
            'username' => 'Admin',
            'email' => 'wilfredsilayo99@gmail.com',
            'firstname'=>'Wilfred',
            'middlename'=>'D',
            'lastname'=>'Silayo',
            'gender'=>'Male',
            'phone'=>'0683415683',
            'maritalstatus'=>'single',
            'nationality'=>'Tanzanian',
            'password' => bcrypt('password'),
        ]);

        Admin::factory()->create([
            'username' => 'AnotherAdmin',
            'email' => 'anotheradmin@example.com',
            'firstname'=>'Another',
            'middlename'=>'M',
            'lastname'=>'Admin',
            'gender'=>'Female',
            'phone'=>'0683415684',
            'maritalstatus'=>'married',
            'nationality'=>'Kenyan',
            'password' => bcrypt('password'),
        ]);
    }
}

    

