<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentAccountController extends Controller
{
    public function index()
    {
        return view('admin.accounts', [
            'students' => User::where('role', 'siswa')
                ->withCount('reports')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'siswa',
        ]);

        return back()->with('status', "Akun siswa \"{$data['name']}\" berhasil dibuat.");
    }

    public function resetPassword(Request $request, User $user)
    {
        abort_if($user->isPetugas(), 403);

        $data = $request->validate([
            'password' => ['required', 'string', 'min:6'],
        ]);

        $user->update(['password' => Hash::make($data['password'])]);

        return back()->with('status', "Password akun \"{$user->name}\" berhasil diganti.");
    }

    public function destroy(User $user)
    {
        abort_if($user->isPetugas(), 403);

        $user->delete();

        return back()->with('status', "Akun \"{$user->name}\" berhasil dihapus. Laporan yang sudah dibuat tetap tersimpan.");
    }
}
