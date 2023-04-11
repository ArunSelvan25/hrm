<?php

use Illuminate\Support\Facades\Auth;
use App\Models\HouseOwner;

function getNotificationsCount() {
    return Auth::guard('admin')->user()->unreadNotifications->count();
}

function getNotifications() {
    return (Auth::guard('admin')->user()->unreadNotifications) ?? '';
}

function getHouseOwnerList() {
    return HouseOwner::where('status',1)->get();
}
