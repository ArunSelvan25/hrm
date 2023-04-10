<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

/**
 *
 */
class AdminAuthController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getLogin(){
        return view('admin.auth.login');
    }

    /**
     * @param AdminLoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postLogin(AdminLoginRequest $request){
        $data = [
            'email' => $request['email'],
            'password' => $request['password']
        ];
        if($this->authAttempt($data,'admin')) {
            return redirect()->route('admin.get-dashboard');
        }
        return back()->with('errors','Credentials not matched');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getdashboard(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin.dashboard')->with('success','Login successfully');
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function adminLogout(){
        Auth::guard('admin')->logout();
        return redirect()->route('admin.get-login');
    }

    /**
     * @return true
     */
    public function deleteNotification(){
        Auth::guard('admin')->user()->notifications()->delete();
        return true;
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsReadNotification($id = ''){
        Auth::guard('admin')->user()->unreadNotifications->where('id',$id)->markAsRead();
        return back()->with('success','Notification marked as read');
    }


}
