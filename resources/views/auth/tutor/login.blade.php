@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Tutor Login</h2>
    
    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="/tutor/login">
        @csrf
        
        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label>Password:</label>
            <input type="password" name="password" required>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="remember"> Remember Me
            </label>
        </div>

        <button type="submit">Login</button>
    </form>

    <div class="links">
        <a href="/tutor/register">Don't have an account? Register</a><br>
        <a href="/student/login">Student Login</a> | 
        <a href="/admin/login">Admin Login</a>
    </div>
</div>
@endsection