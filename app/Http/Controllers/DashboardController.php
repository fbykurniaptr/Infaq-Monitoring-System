<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Infaq;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalInfaq = Infaq::sum('nominal');

        $todayInfaq = Infaq::whereDate('created_at', Carbon::today())->sum('nominal');

        $dailyInfaq = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->format('Y-m-d');
            $dailyInfaq[] = Infaq::whereDate('created_at', $date)->sum('nominal');
        }

        $latestInfaq = Infaq::latest()->take(5)->get();

        $firstInfaqDate = Infaq::orderBy('created_at', 'asc')->first()->created_at ?? Carbon::now();

        return view('welcome', compact('totalInfaq', 'todayInfaq', 'dailyInfaq', 'latestInfaq', 'firstInfaqDate'));
    }
}
