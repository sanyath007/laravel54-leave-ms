<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Holiday;

class HolidayController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('holidays.list');
    }

    public function getHolidays()
    {
        return [
            'holidays' => Holiday::all(),
        ];
    }
}
