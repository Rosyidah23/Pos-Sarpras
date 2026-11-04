<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function student(Request $request)
    {
        $own = Report::where('user_id', $request->user()->id);

        return view('student.dashboard', [
            'recentReports' => (clone $own)->with(['category', 'location'])->latest()->take(5)->get(),
            'counts' => Report::countsByStatus(Report::where('user_id', $request->user()->id)),
        ]);
    }

    public function admin()
    {
        $counts = Report::countsByStatus(Report::query());

        return view('admin.dashboard', [
            'recentReports' => Report::with(['category', 'location', 'user'])->latest()->take(10)->get(),
            'counts' => $counts,
        ]);
    }
}
