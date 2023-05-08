<?php

namespace App\Http\Controllers\Tenant\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantAuthController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getLogin() {
        return view('tenant.auth.login');
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
        if($this->authAttempt($data,'tenant')) {
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
        Auth::guard('tenant')->logout();
        return redirect()->route('tenant.get-login',['locale' => app()->getLocale()]);
    }
}
