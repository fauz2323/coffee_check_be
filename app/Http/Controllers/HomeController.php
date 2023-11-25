<?php

namespace App\Http\Controllers;

use App\Models\HistoryCheck;
use App\Models\UserApps;
use Illuminate\Http\Request;

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
        $userCount = UserApps::count();
        $historyCount = HistoryCheck::count();
        return view('home', compact('userCount', 'historyCount'));
    }
}
