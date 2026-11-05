<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        return view('admin.locations', [
            'locations' => Location::withCount('reports')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:locations,name'],
        ]);

        Location::create($data);

        return back()->with('status', 'Lokasi/ruangan berhasil ditambahkan.');
    }

    public function update(Request $request, Location $location)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:locations,name,'.$location->id],
        ]);

        if ($this->isDenahRoom($location->name) && $data['name'] !== $location->name) {
            return back()->with('error', 'Nama ruangan ini mengikuti denah asli, jadi tidak bisa diubah supaya tetap bisa diklik di denah.');
        }

        $location->update($data);

        return back()->with('status', 'Lokasi/ruangan berhasil diperbarui.');
    }

    /** Apakah nama ini dipakai oleh titik klik di denah (config/denah.php). */
    private function isDenahRoom(string $name): bool
    {
        return collect(config('denah.hotspots'))->contains('name', $name);
    }

    public function destroy(Location $location)
    {
        if ($this->isDenahRoom($location->name)) {
            return back()->with('error', 'Ruangan ini ada di denah asli, jadi tidak bisa dihapus.');
        }

        if ($location->reports()->exists()) {
            return back()->with('error', 'Lokasi tidak bisa dihapus karena masih dipakai laporan.');
        }

        $location->delete();

        return back()->with('status', 'Lokasi/ruangan berhasil dihapus.');
    }
}
