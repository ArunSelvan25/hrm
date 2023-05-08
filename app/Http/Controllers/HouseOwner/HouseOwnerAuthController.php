<?php

namespace App\Http\Controllers\HouseOwner;

use App\Http\Controllers\Controller;
use App\Http\Requests\HouseOwnerRegisterRequest;
use App\Models\{HouseOwner, Admin};
use Illuminate\Support\Facades\{Hash, Crypt, Http};
use Illuminate\Http\Request;
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
            'original_password' => Crypt::encryptString($request['password']),
        ])->assignRole('house-owner');
//        $admin = Admin::first();
//        $admin->notify(new HouseOwnerCreatedNotification($houseOwner));
//        Http::get('127.0.0.1:3000/house-owner-created');
        return back()->with('success','House owner created successfully');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getLogin() {
        return view('house-owner.auth.login');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postLogin(Request $request) {
        $data = [
            'email' => $request['email'],
            'password' => $request['password']
        ];
        if($this->authAttempt($data,'house-owner')) {
            return redirect()->route('get-dashboard',['locale' => app()->getLocale()]);
        }
        return back()->with('errors','Credentials not matched');
    }

    /**
     * @param $data
     * @param $guard
     * @return bool
     */
    public function authAttempt($data, $guard)
    {
        return Auth::guard($guard)->attempt($data);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getdashboard()
    {
        return view('admin.dashboard')->with('success','Login successfully');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(){
        Auth::guard('house-owner')->logout();
        return redirect()->route('house-owner.get-login',['locale' => app()->getLocale()]);
    }


}
