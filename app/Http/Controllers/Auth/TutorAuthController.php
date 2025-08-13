<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
use App\Models\Tutor;

class TutorAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.tutor.login');
    }

    public function showRegisterForm()
    {
        return view('auth.tutor.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:tutors',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string',
            'bio' => 'nullable|string',
            'subjects' => 'nullable|array',
            'hourly_rate' => 'nullable|numeric|min:0',
        ]);

        $tutor = Tutor::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'bio' => $request->bio,
            'subjects' => $request->subjects,
            'hourly_rate' => $request->hourly_rate,
        ]);

        event(new Registered($tutor));

        Auth::guard('tutor')->login($tutor);
        
        return redirect('/tutor/email/verify')->with('message', 'Registration successful! Please verify your email address.');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check if a student or admin is already logged in
        if (Auth::guard('web')->check()) {
            return back()->withErrors(['email' => 'A student is currently logged in. Please <a href="' . route('logout.all') . '" onclick="event.preventDefault(); document.getElementById(\'logout-all-form\').submit();" style="color: #dc3545; text-decoration: underline;">logout all accounts</a> first before logging in as a tutor.'])
                ->with('logout_all_form', true);
        }
        
        if (Auth::guard('admin')->check()) {
            return back()->withErrors(['email' => 'An admin is currently logged in. Please <a href="' . route('logout.all') . '" onclick="event.preventDefault(); document.getElementById(\'logout-all-form\').submit();" style="color: #dc3545; text-decoration: underline;">logout all accounts</a> first before logging in as a tutor.'])
                ->with('logout_all_form', true);
        }

        if (Auth::guard('tutor')->attempt($request->only('email', 'password'), $request->remember)) {
            $tutor = Auth::guard('tutor')->user();
            
            if (!$tutor->hasVerifiedEmail()) {
                Auth::guard('tutor')->logout();
                return back()->withErrors(['email' => 'Please verify your email address before logging in.']);
            }
            
            return redirect()->intended('/tutor/dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout()
    {
        Auth::guard('tutor')->logout();
        return redirect('/tutor/login');
    }

    public function logoutAll()
    {
        // Logout from all guards
        Auth::guard('web')->logout();
        Auth::guard('tutor')->logout();
        Auth::guard('admin')->logout();
        
        // Clear session
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        
        return redirect('/')->with('message', 'You have been logged out from all accounts.');
    }

    public function dashboard()
    {
        return view('tutor.dashboard');
    }

    public function showEmailVerificationForm()
    {
        return view('auth.tutor.verify-email');
    }

    public function verifyEmail(Request $request, $id, $hash)
    {
        // Validate the request has the required parameters
        if (!$request->hasValidSignature()) {
            // If user is not logged in, show special error page
            if (!Auth::guard('tutor')->check()) {
                return view('auth.tutor.verification-error');
            }
            return redirect('/tutor/email/verify')->withErrors(['email' => 'Invalid or expired verification link. Please request a new verification email.']);
        }

        try {
            $tutor = Tutor::findOrFail($id);
        } catch (\Exception $e) {
            // If user is not logged in, show special error page
            if (!Auth::guard('tutor')->check()) {
                return view('auth.tutor.verification-error');
            }
            return redirect('/tutor/email/verify')->withErrors(['email' => 'Invalid verification link. Please request a new verification email.']);
        }
        
        if (!hash_equals($hash, sha1($tutor->getEmailForVerification()))) {
            // If user is not logged in, show special error page
            if (!Auth::guard('tutor')->check()) {
                return view('auth.tutor.verification-error');
            }
            Auth::guard('tutor')->login($tutor);
            return redirect('/tutor/email/verify')->withErrors(['email' => 'Invalid verification link. Please request a new verification email.']);
        }
        
        if ($tutor->hasVerifiedEmail()) {
            Auth::guard('tutor')->login($tutor);
            return redirect('/tutor/dashboard');
        }

        if ($tutor->markEmailAsVerified()) {
            Auth::guard('tutor')->login($tutor);
            return redirect('/tutor/dashboard')->with('message', 'Email verified successfully!');
        }

        Auth::guard('tutor')->login($tutor);
        return redirect('/tutor/email/verify')->withErrors(['email' => 'Unable to verify email. Please try again.']);
    }

    public function resendEmailVerification(Request $request)
    {
        $tutor = Auth::guard('tutor')->user();
        
        if (!$tutor) {
            return redirect('/tutor/login')->withErrors(['email' => 'Please log in to resend verification email.']);
        }
        
        if ($tutor->hasVerifiedEmail()) {
            return redirect('/tutor/dashboard');
        }

        $tutor->sendEmailVerificationNotification();

        return back()->with('message', 'A new verification email has been sent to your email address!');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.tutor.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $tutor = Tutor::where('email', $request->email)->first();

        if (!$tutor) {
            return back()->withErrors(['email' => 'We couldn\'t find a tutor with that email address.']);
        }

        $token = Str::random(64);

        DB::table('tutor_password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => Hash::make($token),
                'created_at' => now()
            ]
        );

        $tutor->sendPasswordResetNotification($token);

        return back()->with('message', 'Password reset link sent to your email!');
    }

    public function showResetForm($token)
    {
        return view('auth.tutor.reset-password', ['token' => $token]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $tokenData = DB::table('tutor_password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$tokenData || !Hash::check($request->token, $tokenData->token)) {
            return back()->withErrors(['email' => 'Invalid or expired reset token.']);
        }

        // Check if token is expired (60 minutes)
        if (now()->diffInMinutes($tokenData->created_at) > 60) {
            return back()->withErrors(['email' => 'Reset token has expired.']);
        }

        $tutor = Tutor::where('email', $request->email)->first();
        
        if (!$tutor) {
            return back()->withErrors(['email' => 'Tutor not found.']);
        }

        $tutor->password = Hash::make($request->password);
        $tutor->save();

        DB::table('tutor_password_reset_tokens')->where('email', $request->email)->delete();

        return redirect('/tutor/login')->with('message', 'Password reset successfully!');
    }
}