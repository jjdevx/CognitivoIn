<?php

namespace App\Http\Controllers\Backoffice;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     return view('back_office.dashboard');
    // }
    //
    // public function indexDashboard()
    // {
    //     return view('back_office.dashboard');
    // }
    //
    // public function indexProfile()
    // {
    //     return view('back_office.profile');
    // }
}
