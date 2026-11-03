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
            'counts' => $this->countByStatus($own),
        ]);
    }

    public function admin()
    {
        $counts = $this->countByStatus(Report::query());

        return view('admin.dashboard', [
            'recentReports' => Report::with(['category', 'location', 'user'])->latest()->take(10)->get(),
            'total' => $counts->sum(),
            'perluDicek' => $counts['menunggu'],
            'selesaiCount' => $counts['selesai'],
        ]);
    }

    /** Jumlah laporan per status; status tanpa laporan tetap muncul dengan nilai 0. */
    private function countByStatus($query)
    {
        $totals = $query->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');

        return collect(Report::STATUSES)->mapWithKeys(fn ($status) => [$status => (int) $totals->get($status, 0)]);
    }
}
