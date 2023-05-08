<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAuthController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getLogin() {
        return view('user.auth.login');
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
        if($this->authAttempt($data,'web')) {
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
        Auth::guard('web')->logout();
        return redirect()->route('web.get-login',['locale' => app()->getLocale()]);
    }
}
