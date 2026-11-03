<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportFilterRequest;
use App\Models\Report;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function index(ReportFilterRequest $request)
    {
        $filters = $request->validated();

        $reports = Report::with(['category', 'location', 'user'])
            ->filtered($filters)
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.reports', [
            'reports' => $reports,
            'from' => $filters['from'] ?? null,
            'to' => $filters['to'] ?? null,
            'statusFilter' => $filters['status'] ?? null,
            'statuses' => Report::STATUSES,
        ]);
    }

    public function update(Request $request, Report $report)
    {
        $data = $request->validate([
            'status' => ['required', 'in:'.implode(',', Report::STATUSES)],
            'follow_up_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $report->update([
            'status' => $data['status'],
            'follow_up_note' => $data['follow_up_note'] ?? null,
            'handled_by' => $request->user()->id,
        ]);

        return back()->with('status', 'Status laporan #'.$report->id.' berhasil diperbarui.');
    }
}
