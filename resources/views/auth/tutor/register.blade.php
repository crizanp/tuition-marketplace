@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="flex w-full max-w-5xl bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="flex-1 flex flex-col justify-center items-center bg-gray-50 p-8">
            <div class="w-full max-w-xs">
                <h2 class="text-xl font-bold text-blue-700 mb-6 text-center">Find Home Tuition / Online Tuition Part Time Jobs.</h2>
                <div class="flex items-center justify-center gap-4 mb-6">
                    <div class="flex flex-col items-center">
                        <div class="bg-white border border-gray-200 rounded-lg shadow p-4 w-24 text-center">
                            <div class="font-semibold text-gray-800">Create <span class="text-orange-500 font-bold">PROFILE</span></div>
                            <div class="text-blue-700 font-bold text-lg mt-1">1</div>
                        </div>
                    </div>
                    <span class="text-2xl text-gray-400">&#x2192;</span>
                    <div class="flex flex-col items-center">
                        <div class="bg-white border border-gray-200 rounded-lg shadow p-4 w-24 text-center">
                            <div class="font-semibold text-gray-800">Get <span class="text-orange-500 font-bold">STUDENTS</span></div>
                            <div class="text-blue-700 font-bold text-lg mt-1">2</div>
                        </div>
                    </div>
                    <span class="text-2xl text-gray-400">&#x2192;</span>
                    <div class="flex flex-col items-center">
                        <div class="bg-white border border-gray-200 rounded-lg shadow p-4 w-24 text-center">
                            <div class="font-semibold text-gray-800">Start <span class="text-orange-500 font-bold">EARNING</span></div>
                            <div class="text-blue-700 font-bold text-lg mt-1">3</div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-center mb-4">
                    <img src="/images/tuition-image.jpg" alt="Tuition Illustration" class="rounded-xl shadow w-64 max-w-full">
                </div>
                <div class="bg-white text-green-600 font-semibold text-center rounded-md py-2 mt-2 shadow-sm">
                    You focus on teaching, We focus on finding students for you.
                </div>
            </div>
        </div>
        <div class="flex-1 flex flex-col justify-center px-8 py-12 max-w-md mx-auto">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Tutor Registration</h2>
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <form method="POST" action="/tutor/register" class="space-y-4">
                @csrf
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Name:</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Email:</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Phone:</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Password:</label>
                    <input type="password" name="password" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Confirm Password:</label>
                    <input type="password" name="password_confirmation" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Bio:</label>
                    <textarea name="bio" rows="3" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">{{ old('bio') }}</textarea>
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Hourly Rate ($):</label>
                    <input type="number" name="hourly_rate" step="0.01" value="{{ old('hourly_rate') }}" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded transition">Register</button>
            </form>
            <div class="text-left mt-6">
                <a href="/tutor/login" class="text-blue-600 hover:underline font-medium">Already have an account? Login</a>
            </div>
        </div>
    </div>
</div>
@endsection