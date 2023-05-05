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

        for($i=1; $i <= 100; $i++){
            HouseOwner::create([
                'name' => 'HouseOwner '.$faker->name(28),
                'email' => 'HouseOwner '.$faker->unique()->safeEmail,
                'password' => Hash::make('Qwert@12345'),
                'original_password' => Crypt::encryptString('Qwert@12345'),
            ]);
        }

        $houseOwners = HouseOwner::all();
        foreach($houseOwners as $houseOwner){
            for($i=1; $i <= 4; $i++){
                Property::create([
                    'house_owner_id' => $houseOwner->id,
                    'title' => 'Property '.$houseOwner->name.' - '.$i,
                ]);
            }
        }

        $properties = Property::all();
        foreach($properties as $property){
            for($i=1; $i <= 4; $i++){
                Tenant::create([
                    'property_id' => $property->id,
                    'name' => 'Tenant '.$faker->name(28),
                    'email' => 'Tenant '.$faker->unique()->safeEmail,
                    'password' => Hash::make('Qwert@12345'),
                    'original_password' => Crypt::encryptString('Qwert@12345'),
                ]);
            }
        }

        $tenants = Tenant::all();
        foreach($tenants as $tenant){
            for($i=1; $i <= 4; $i++){
                User::create([
                    'tenant_id' => $tenant->id,
                    'name' => 'User '.$faker->name(28),
                    'email' => 'User '.$faker->unique()->safeEmail,
                    'password' => Hash::make('Qwert@12345'),
                    'original_password' => Crypt::encryptString('Qwert@12345'),
                ]);
            }
        }
        
    }
}
