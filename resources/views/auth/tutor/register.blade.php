@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Tutor Registration</h2>
    
    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="/tutor/register">
        @csrf
        
        <div class="form-group">
            <label>Name:</label>
            <input type="text" name="name" value="{{ old('name') }}" required>
        </div>

        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label>Phone:</label>
            <input type="text" name="phone" value="{{ old('phone') }}">
        </div>

        <div class="form-group">
            <label>Password:</label>
            <input type="password" name="password" required>
        </div>

        <div class="form-group">
            <label>Confirm Password:</label>
            <input type="password" name="password_confirmation" required>
        </div>

        <div class="form-group">
            <label>Bio:</label>
            <textarea name="bio" rows="3">{{ old('bio') }}</textarea>
        </div>

        <div class="form-group">
            <label>Hourly Rate ($):</label>
            <input type="number" name="hourly_rate" step="0.01" value="{{ old('hourly_rate') }}">
        </div>

        <button type="submit">Register</button>
    </form>

    <div class="links">
        <a href="/tutor/login">Already have an account? Login</a>
    </div>
</div>
@endsection