<?php

namespace App\Http\Controllers\HouseOwner;

use App\Http\Controllers\Controller;
use App\Http\Requests\HouseOwnerRegisterRequest;
use App\Models\{HouseOwner, Admin};
use Illuminate\Support\Facades\{Hash, Crypt, Http};
use App\Notifications\HouseOwnerCreatedNotification;
use Illuminate\Support\Facades\Auth;

/**
 *
 */
class HouseOwnerAuthController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getRegister(){
        return view('house-owner.auth.register');
    }

    /**
     * @param HouseOwnerRegisterRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postRegister(HouseOwnerRegisterRequest $request){
        $houseOwner = HouseOwner::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'original_password' => Crypt::encryptString('Qwert@12345'),
        ]);
        $admin = Admin::first();
        $admin->notify(new HouseOwnerCreatedNotification($houseOwner));
        Http::get('127.0.0.1:3000/house-owner-created');
        return back()->with('success','House owner created successfully');
    }


}
