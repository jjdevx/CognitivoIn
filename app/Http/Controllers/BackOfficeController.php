<?php

namespace App\Http\Controllers;

use App\Profile;
use Illuminate\Http\Request;

class BackOfficeController extends Controller
{
    /**
    * Show the application dashboard.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Profile $profile)
    {
        if (isset($profile))
        {
            return view('back_office.index');
        }
        else
        {
            return view('home');
        }
    }

    public function indexDashboard()
    {
        return view('back_office.dashboard');
    }

    public function indexProfile()
    {
        return view('back_office.profile');
    }

    public function indexItems()
    {
        return view('back_office.items');
    }
}
