<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        if(!isset(Auth::user()->id)){
            return view('auth.login');
        } else if (isset(Auth::user()->id)){

            $tasks = Auth::user()->tasks;
            return view('home', compact('tasks'));
        } else {
            return view('auth.login');
        }
    }
}
