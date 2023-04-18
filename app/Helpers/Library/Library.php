<?php

use Illuminate\Support\Facades\Auth;
use App\Models\{HouseOwner, Property, Tenant};

function getNotificationsCount() {
//    return Auth::guard('admin')->user()->unreadNotifications->count();
}

function getNotifications() {
//    return (Auth::guard('admin')->user()->unreadNotifications) ?? '';
}

function getHouseOwnerList() {
    return HouseOwner::where('status',1)->get();
}

function getPropertyList() {
    return Property::where('status',1)->get();
}

function getTenantList() {
    return Tenant::where('status',1)->get();
}

function getGuard(){
    if(Auth::guard('admin')->check())
    {return "admin";}
    elseif(Auth::guard('house-owner')->check())
    {return "house-owner";}
    elseif(Auth::guard('tenant')->check())
    {return "tenant";}
    elseif(Auth::guard('web')->check())
    {return "web";}
}
