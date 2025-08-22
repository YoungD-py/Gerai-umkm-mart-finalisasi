<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function adminLogin()
    {
        return view('admin-login');
    }

    public function errorUnauthorized()
    {
        return view('error-unauthorized');
    }

    public function errorAdminOnly()
    {
        return view('error-admin-only');
    }

    public function storeMathCaptcha(Request $request)
    {
        session(['math_captcha_answer' => $request->answer]);
        return response()->json(['success' => true]);
    }

    public function storeCaptcha(Request $request)
    {
        session(['admin_captcha' => $request->captcha]);
        return response()->json(['success' => true]);
    }

    /**
     * Handle an authentication attempt for kasir.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        // Math captcha validation for kasir
        $captcha_answer = $request->input('captcha_answer');
        $session_answer = $request->session()->get('math_captcha_answer');
        
        if (empty($captcha_answer)) {
            return back()->with('loginError', 'Captcha harus diisi!');
        }
        
        if ((int)$captcha_answer !== (int)$session_answer) {
            return back()->with('loginError', 'Jawaban matematika salah!');
        }

        // Check if user exists and is kasir
        $user = User::where('username', $credentials['username'])->first();
        if (!$user || $user->isAdmin()) {
            return back()->with('loginError', 'Username/Password/Captcha Anda Salah, Coba Lagi!');
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $request->session()->forget('math_captcha_answer'); // Clear captcha from session

            return redirect()
                ->intended('/dashboard')
                ->with('success', 'Selamat Datang di Dashboard KasirKu');
        }
        return back()->with('loginError', 'Username/Password/Captcha Anda Salah, Coba Lagi!');
    }

    /**
     * Handle an authentication attempt for admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticateAdmin(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        // Standard captcha validation for admin
        $captcha_code = $request->input('captcha_code');
        $session_captcha = $request->session()->get('admin_captcha');
        
        if (empty($captcha_code)) {
            return back()->with('loginError', 'Captcha harus diisi!');
        }
        
        if (strtoupper($captcha_code) !== strtoupper($session_captcha)) {
            return back()->with('loginError', 'Captcha salah, coba lagi!');
        }

        $user = User::where('username', $credentials['username'])->first();
        if (!$user || !$user->isAdminOrManajer()) {
            return back()->with('loginError', 'Username/Password/Captcha Anda Salah, Coba Lagi!');
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $request->session()->forget('admin_captcha'); // Clear captcha from session

            $welcomeMessage = $user->isAdmin() ? 'Selamat Datang di Dashboard Admin' : 'Selamat Datang di Dashboard Manajer';
            
            return redirect()
                ->intended('/dashboard')
                ->with('success', $welcomeMessage);
        }
        return back()->with('loginError', 'Username/Password/Captcha Anda Salah, Coba Lagi!');
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
