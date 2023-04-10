<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{Hash, Crypt};

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'name' => 'Admin',
            'email' => 'superuser@mailinator.com',
            'password' => Hash::make('Qwert@12345'),
            'original_password' => Crypt::encryptString('Qwert@12345'),
        ]);
    }
}
