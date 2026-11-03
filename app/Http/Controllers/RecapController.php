<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportFilterRequest;
use App\Models\Report;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RecapController extends Controller
{
    public function index(ReportFilterRequest $request)
    {
        $filters = $request->validated();
        $reports = $this->reports($filters);

        $today = Carbon::today();

        return view('admin.recap', [
            'reports' => $reports,
            'from' => $filters['from'] ?? null,
            'to' => $filters['to'] ?? null,
            'status' => $filters['status'] ?? null,
            'statuses' => Report::STATUSES,
            'summary' => $reports->countBy('status'),
            'periodLabel' => $this->periodLabel($filters['from'] ?? null, $filters['to'] ?? null),
            'presets' => [
                'Hari ini' => ['from' => $today->toDateString(), 'to' => $today->toDateString()],
                '7 hari terakhir' => ['from' => $today->copy()->subDays(6)->toDateString(), 'to' => $today->toDateString()],
                '30 hari terakhir' => ['from' => $today->copy()->subDays(29)->toDateString(), 'to' => $today->toDateString()],
                'Bulan ini' => ['from' => $today->copy()->startOfMonth()->toDateString(), 'to' => $today->toDateString()],
            ],
        ]);
    }

    public function export(ReportFilterRequest $request): StreamedResponse
    {
        $filters = $request->validated();
        $reports = $this->reports($filters);

        $range = ($filters['from'] ?? null) || ($filters['to'] ?? null)
            ? ($filters['from'] ?? 'awal').'_sampai_'.($filters['to'] ?? now()->format('Y-m-d'))
            : 'semua-'.now()->format('Y-m-d');

        return response()->streamDownload(function () use ($reports) {
            $out = fopen('php://output', 'w');

            // BOM supaya Excel membaca UTF-8 dengan benar; titik koma karena Excel
            // berlokal Indonesia memakai ; sebagai pemisah kolom.
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['No', 'Kode', 'Tanggal', 'Judul', 'Kategori', 'Lokasi', 'Pelapor', 'Status', 'Deskripsi', 'Catatan petugas'], ';');

            foreach ($reports as $i => $report) {
                fputcsv($out, array_map($this->safeCell(...), [
                    $i + 1,
                    $report->code,
                    $report->created_at->format('d-m-Y H:i'),
                    $report->title,
                    $report->category?->name,
                    $report->location?->name,
                    $report->user?->name ?? '-',
                    ucfirst($report->status),
                    $report->description,
                    $report->follow_up_note,
                ]), ';');
            }

            fclose($out);
        }, "laporan-sarpras-{$range}.csv", ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function reports(array $filters)
    {
        return Report::with(['category', 'location', 'user'])
            ->filtered($filters)
            ->orderBy('created_at')
            ->orderBy('id')
            ->get();
    }

    private function periodLabel(?string $from, ?string $to): string
    {
        $format = fn (string $date) => Carbon::parse($date)->translatedFormat('d F Y');

        return match (true) {
            $from && $to => $from === $to ? $format($from) : $format($from).' s.d. '.$format($to),
            (bool) $from => 'Sejak '.$format($from),
            (bool) $to => 'Sampai '.$format($to),
            default => 'Semua tanggal',
        };
    }

    /** Cegah teks berawalan = + - @ dieksekusi sebagai rumus saat CSV dibuka di Excel. */
    private function safeCell(mixed $value): mixed
    {
        return is_string($value) && preg_match('/^[=+\-@\t\r]/', $value) ? "'".$value : $value;
    }
}
