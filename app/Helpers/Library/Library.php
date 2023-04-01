<?php

use Illuminate\Support\Facades\Auth;

function getNotificationsCount() {
    return Auth::guard('admin')->user()->unreadNotifications->count();
}

function getNotifications() {
    return (Auth::guard('admin')->user()->unreadNotifications) ?? '';
}
