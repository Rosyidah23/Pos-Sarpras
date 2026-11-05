<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportFilterRequest;
use App\Models\Category;
use App\Models\Location;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ReportController extends Controller
{
    public function create(Request $request)
    {
        return view('student.report-form', [
            'categories' => Category::orderBy('name')->get(),
            'locations' => Location::orderBy('name')->get(),
            'selectedLocationId' => $request->filled('location_id') ? (int) $request->query('location_id') : null,
        ]);
    }

    public function map()
    {
        // Semua ruangan di denah harus bisa diklik, jadi pastikan tiap nama di config/denah.php
        // ada di tabel locations (yang belum ada dibuatkan otomatis).
        $existing = Location::pluck('id', 'name');
        collect(config('denah.hotspots'))->pluck('name')->unique()
            ->reject(fn ($name) => $existing->has($name))
            ->each(fn ($name) => Location::firstOrCreate(['name' => $name]));

        // Titik pada denah dicocokkan ke lokasi lewat nama ruangan.
        $locationIds = Location::pluck('id', 'name');

        $hotspots = collect(config('denah.hotspots'))
            ->map(fn ($spot) => $spot + ['location_id' => $locationIds[$spot['name']] ?? null])
            ->filter(fn ($spot) => $spot['location_id'] !== null)
            ->values();

        return view('student.map', [
            'mapImage' => config('denah.image'),
            'hotspots' => $hotspots,
        ]);
    }

    public function history(ReportFilterRequest $request)
    {
        $filters = $request->validated();

        $reports = Report::with(['category', 'location'])
            ->where('user_id', $request->user()->id)
            ->filtered($filters)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // Hitungan per status mengikuti filter tanggal, tapi tidak filter status, supaya semua tab tetap punya angka.
        $counts = Report::countsByStatus(
            Report::where('user_id', $request->user()->id)->filtered(Arr::only($filters, ['from', 'to']))
        );

        return view('student.history', [
            'reports' => $reports,
            'counts' => $counts,
            'statusFilter' => $filters['status'] ?? null,
            'from' => $filters['from'] ?? null,
            'to' => $filters['to'] ?? null,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'location_id' => ['required', 'exists:locations,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'photo' => ['required', 'image', 'max:4096'],
        ]);

        Report::create([
            'user_id' => $request->user()->id,
            'category_id' => $data['category_id'],
            'location_id' => $data['location_id'],
            'title' => $data['title'],
            'description' => $data['description'],
            'photo_path' => $request->file('photo')->store('reports', 'public'),
            'status' => 'menunggu',
        ]);

        return redirect()
            ->route('dashboard')
            ->with('status', 'Laporan berhasil dikirim. Status: Menunggu.');
    }
}
