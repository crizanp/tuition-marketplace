<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
use App\Models\User;

class StudentAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.student.login');
    }

    public function showRegisterForm()
    {
        return view('auth.student.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string',
            'grade_level' => 'nullable|string',
            'preferred_subjects' => 'nullable|array',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'grade_level' => $request->grade_level,
            'preferred_subjects' => $request->preferred_subjects,
        ]);

        event(new Registered($user));

        Auth::login($user);
        
        return redirect('/student/email/verify')->with('message', 'Registration successful! Please verify your email address.');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->remember)) {
            $user = Auth::user();
            
            if (!$user->hasVerifiedEmail()) {
                Auth::logout();
                return back()->withErrors(['email' => 'Please verify your email address before logging in.']);
            }
            
            return redirect()->intended('/student/dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/student/login');
    }

    public function dashboard()
    {
        return view('student.dashboard');
    }

    public function showEmailVerificationForm()
    {
        return view('auth.student.verify-email');
    }

    public function verifyEmail(Request $request, $id, $hash)
    {
        // Validate the request has the required parameters
        if (!$request->hasValidSignature()) {
            // If user is not logged in, show special error page
            if (!Auth::check()) {
                return view('auth.student.verification-error');
            }
            return redirect('/student/email/verify')->withErrors(['email' => 'Invalid or expired verification link. Please request a new verification email.']);
        }

        try {
            $user = User::findOrFail($id);
        } catch (\Exception $e) {
            // If user is not logged in, show special error page
            if (!Auth::check()) {
                return view('auth.student.verification-error');
            }
            return redirect('/student/email/verify')->withErrors(['email' => 'Invalid verification link. Please request a new verification email.']);
        }
        
        if (!hash_equals($hash, sha1($user->getEmailForVerification()))) {
            // If user is not logged in, show special error page
            if (!Auth::check()) {
                return view('auth.student.verification-error');
            }
            Auth::login($user);
            return redirect('/student/email/verify')->withErrors(['email' => 'Invalid verification link. Please request a new verification email.']);
        }
        
        if ($user->hasVerifiedEmail()) {
            Auth::login($user);
            return redirect('/student/dashboard');
        }

        if ($user->markEmailAsVerified()) {
            Auth::login($user);
            return redirect('/student/dashboard')->with('message', 'Email verified successfully!');
        }

        Auth::login($user);
        return redirect('/student/email/verify')->withErrors(['email' => 'Unable to verify email. Please try again.']);
    }

    public function resendEmailVerification(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect('/student/login')->withErrors(['email' => 'Please log in to resend verification email.']);
        }
        
        if ($user->hasVerifiedEmail()) {
            return redirect('/student/dashboard');
        }

        $user->sendEmailVerificationNotification();

        return back()->with('message', 'A new verification email has been sent to your email address!');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.student.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'We couldn\'t find a student with that email address.']);
        }

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => Hash::make($token),
                'created_at' => now()
            ]
        );

        $user->sendPasswordResetNotification($token);

        return back()->with('message', 'Password reset link sent to your email!');
    }

    public function showResetForm($token)
    {
        return view('auth.student.reset-password', ['token' => $token]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $tokenData = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$tokenData || !Hash::check($request->token, $tokenData->token)) {
            return back()->withErrors(['email' => 'Invalid or expired reset token.']);
        }

        // Check if token is expired (60 minutes)
        if (now()->diffInMinutes($tokenData->created_at) > 60) {
            return back()->withErrors(['email' => 'Reset token has expired.']);
        }

        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->withErrors(['email' => 'Student not found.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect('/student/login')->with('message', 'Password reset successfully!');
    }
}