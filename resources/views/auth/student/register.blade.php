@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Student Registration</h2>
    
    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="/student/register">
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
            <label>Grade Level:</label>
            <input type="text" name="grade_level" value="{{ old('grade_level') }}">
        </div>

        <button type="submit">Register</button>
    </form>

    <div class="links">
        <a href="/student/login">Already have an account? Login</a>
    </div>
</div>
@endsection