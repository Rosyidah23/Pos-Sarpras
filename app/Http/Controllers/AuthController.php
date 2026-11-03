<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        if ($request->user()) {
            return $this->home($request);
        }

        return view('auth.login', [
            'monthTotal' => Report::whereYear('created_at', now()->year)->whereMonth('created_at', now()->month)->count(),
            'doneTotal' => Report::where('status', 'selesai')->count(),
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'NIS/username atau password salah.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return $this->home($request);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function home(Request $request)
    {
        return redirect()->intended(
            $request->user()->isPetugas() ? route('admin.dashboard') : route('dashboard')
        );
    }
}
