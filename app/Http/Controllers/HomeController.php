<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the home page or redirect to dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // For now, just redirect to the calendar
        return redirect()->route('calendar.index');
    }

    /**
     * Show the dashboard page.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        return view('dashboard');
    }
}
