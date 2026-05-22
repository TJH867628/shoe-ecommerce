<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function loginPage()
    {
        return view('login');
    }

    public function registerPage()
    {
        return view("register");
    }
}
