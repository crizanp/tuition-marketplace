@extends('layouts.app')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-left">
            <div class="steps-section">
                <div class="steps-top">
                    <h2 class="steps-title">Find Home Tuition / Online Tuition Part Time Jobs.</h2>
                    <div class="steps-row">
                        <div class="step-box">
                            <div class="step-label">Create <span>PROFILE</span></div>
                            <div class="step-num">1</div>
                        </div>
                        <span class="step-arrow">&#x2192;</span>
                        <div class="step-box">
                            <div class="step-label">Get <span>STUDENTS</span></div>
                            <div class="step-num">2</div>
                        </div>
                        <span class="step-arrow">&#x2192;</span>
                        <div class="step-box">
                            <div class="step-label">Start <span>EARNING</span></div>
                            <div class="step-num">3</div>
                        </div>
                    </div>
                </div>
                <div class="tuition-illustration">
                    <img src="/images/tuition-image.jpg" alt="Tuition Illustration" class="tuition-img-big">
                </div>
                <div class="steps-footer">
                    <span>You focus on teaching, We focus on finding students for you.</span>
                </div>
            </div>
        </div>
        <div class="auth-right">
            <h2 class="form-title">Tutor Registration</h2>
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
                <button type="submit" class="register-btn">Register</button>
            </form>
            <div class="links">
                <a href="/tutor/login">Already have an account? Login</a>
            </div>
        </div>
    </div>
</div>

<style>
.auth-wrapper {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}
.auth-card {
    display: flex;
    background: #222;
    border-radius: 18px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.18);
    overflow: hidden;
    width: 100%;
}
.auth-left {
    background: #f7f7f7;
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 3;
    min-height: 520px;
    padding: 0;
}
.tuition-illustration {
    margin: 0 auto 8px auto;
    text-align: center;
}
.tuition-img-big {
    width: 320px;
    max-width: 95%;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.10);
    display: block;
    margin: 0 auto;
}
.steps-section {
    width: 100%;
    text-align: center;
    padding: 0 18px;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: center;
}
.steps-top {
    margin-bottom: 8px;
}
.steps-title {
    color: #2176ae;
    font-size: 1.15em;
    font-weight: 700;
    margin-bottom: 8px;
    margin-top: 12px;
}
.steps-row {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-bottom: 0;
}
.step-box {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    padding: 12px 22px;
    min-width: 110px;
    text-align: center;
    border: 1px solid #e0e0e0;
}
.step-label {
    font-size: 1.08em;
    color: #222;
    font-weight: 600;
    margin-bottom: 4px;
}
.step-label span {
    color: #ff6600;
    font-weight: 700;
}
.step-num {
    color: #2176ae;
    font-size: 1.15em;
    font-weight: 700;
    margin-top: 2px;
}
.step-arrow {
    font-size: 2em;
    color: #bbb;
    margin: 0 4px;
}
.steps-footer {
    background: #fff;
    color: #66bb6a;
    font-size: 1.08em;
    font-weight: 600;
    margin: 10px 0 0 0;
    padding: 7px 0;
    border-radius: 6px;
}
.auth-right {
    background: #fff;
    padding: 38px 32px 32px 32px;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    border-left: 1px solid #eee;
    min-width: 320px;
    max-width: 400px;
}
.form-title {
    margin-bottom: 18px;
    color: #222;
    font-size: 1.6em;
    font-weight: 700;
    text-align: left;
}
.form-group {
    margin-bottom: 15px;
}
label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    color: #333;
}
input, textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 15px;
    background: #fafafa;
    color: #222;
    box-sizing: border-box;
}
input:focus, textarea:focus {
    border-color: #339fff;
    outline: none;
}
.register-btn {
    background: #339fff;
    color: #fff;
    padding: 12px 0;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 1.1em;
    font-weight: 600;
    margin-top: 10px;
    transition: background 0.2s;
}
.register-btn:hover {
    background: #007bff;
}
.error {
    color: #dc3545;
    font-size: 14px;
    margin-bottom: 10px;
}
.links {
    text-align: left;
    margin-top: 18px;
}
.links a {
    color: #339fff;
    text-decoration: none;
    font-weight: 500;
}
@media (max-width: 900px) {
    .auth-card {
        flex-direction: column;
        max-width: 98vw;
    }
    .auth-left {
        width: 100%;
        min-height: 220px;
        padding: 18px 0;
        flex: unset;
    }
    .auth-right {
        border-left: none;
        border-top: 1px solid #eee;
        padding: 28px 18px 18px 18px;
        min-width: unset;
        max-width: unset;
        flex: unset;
    }
    .tuition-img-big {
        max-width: 180px;
        width: 100%;
    }
}
@media (max-width: 600px) {
    .auth-card {
        border-radius: 8px;
    }
    .auth-right {
        padding: 18px 8px 8px 8px;
    }
    .form-title {
        font-size: 1.2em;
    }
    .steps-title {
        font-size: 1em;
    }
    .steps-footer {
        font-size: 1em;
    }
}
</style>
@endsection