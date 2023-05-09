<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{HouseOwner, Property, Tenant, User};
use Illuminate\Support\Facades\{Hash, Crypt};
use Illuminate\Support\Str;

class DataEntriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();


        // Create house owners
        for($i=1; $i <= 5; $i++){
            HouseOwner::create([
                'name' => 'Owner '.$faker->name(),
                'email' => 'owner'.Str::random(8).'@mailinator.com',
                'password' => Hash::make('Qwert@12345'),
                'original_password' => Crypt::encryptString('Qwert@12345'),
            ])->assignRole('house-owner');
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
            Tenant::create([
                'property_id' => $property->id,
                'name' => 'Tenant '.$faker->name(),
                'email' => 'tenant'.Str::random(8).'@mailinator.com',
                'password' => Hash::make('Qwert@12345'),
                'original_password' => Crypt::encryptString('Qwert@12345'),
            ])->assignRole('tenant');
        }

        $tenants = Tenant::all();
        foreach($tenants as $tenant){
            for($i=1; $i <= 3; $i++){
                User::create([
                    'tenant_id' => $tenant->id,
                    'name' => 'User '.$faker->name(),
                    'email' => 'user'.Str::random(8).'@mailinator.com',
                    'password' => Hash::make('Qwert@12345'),
                    'original_password' => Crypt::encryptString('Qwert@12345'),
                ])->assignRole('web');
            }
        }
        
    }
}
