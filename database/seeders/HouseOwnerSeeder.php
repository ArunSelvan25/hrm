<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{HouseOwner, Property, Tenant, User};
use Illuminate\Support\Facades\{Hash, Crypt};

class HouseOwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        for($i=0; $i <= 100; $i++){
            HouseOwner::create([
                'name' => $faker->name(),
                'email' => $faker->safeEmail,
                'password' => Hash::make('Qwert@12345'),
                'original_password' => Crypt::encryptString('Qwert@12345'),
            ]);
        }

        $houseOwners = HouseOwner::all();
        foreach($houseOwners as $houseOwner){
            for($i=0; $i <= 4; $i++){
                Property::create([
                    'house_owner_id' => $houseOwner->id,
                    'title' => $houseOwner->name.' property '.$i,
                ]);
            }
        }
        
    }
}
