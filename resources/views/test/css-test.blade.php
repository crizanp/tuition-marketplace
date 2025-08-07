@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4">
    <div class="max-w-4xl mx-auto">
        <!-- Test Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">CSS Framework Test Page</h1>
            <p class="text-xl text-gray-600">Testing Bootstrap + Tailwind CSS Integration</p>
        </div>

        <!-- Bootstrap Components Test -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Bootstrap Components</h2>
            <div class="container-fluid">
                <div class="row g-3">
                    <div class="col-md-6">
                        <button class="btn btn-primary btn-lg w-100">Bootstrap Primary Button</button>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-success btn-lg w-100">Bootstrap Success Button</button>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <strong>Bootstrap Alert:</strong> This is a Bootstrap alert component.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tailwind Components Test -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Tailwind CSS Utilities</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-300 shadow-md hover:shadow-lg">
                    Tailwind Blue Button
                </button>
                <button class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-300 shadow-md hover:shadow-lg">
                    Tailwind Green Button
                </button>
                <button class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-300 shadow-md hover:shadow-lg">
                    Tailwind Purple Button
                </button>
                <button class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-300 shadow-md hover:shadow-lg">
                    Tailwind Red Button
                </button>
            </div>
        </div>

        <!-- Mixed Approach Test -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Mixed Approach (Bootstrap + Tailwind)</h2>
            <div class="container-fluid">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="bg-gradient-to-r from-cyan-400 to-blue-500 text-white p-6 rounded-lg shadow-md">
                            <h3 class="text-xl font-bold mb-2">Bootstrap Grid + Tailwind Styling</h3>
                            <p class="text-cyan-100">This card uses Bootstrap's grid system with Tailwind's gradient and styling utilities.</p>
                            <button class="mt-4 bg-white text-blue-600 hover:bg-gray-100 font-semibold py-2 px-4 rounded-md transition-colors">
                                Learn More
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="bg-gradient-to-r from-pink-400 to-red-500 text-white p-6 rounded-lg shadow-md">
                            <h3 class="text-xl font-bold mb-2">Best of Both Worlds</h3>
                            <p class="text-pink-100">Bootstrap provides structure while Tailwind provides beautiful utilities and custom styling.</p>
                            <button class="mt-4 bg-white text-red-600 hover:bg-gray-100 font-semibold py-2 px-4 rounded-md transition-colors">
                                Get Started
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Test -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Form Elements Test</h2>
            <form class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bootstrap Form Input:</label>
                        <input type="text" class="form-control form-control-lg" placeholder="Bootstrap styled input">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tailwind Form Input:</label>
                        <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="Tailwind styled input">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mixed Approach Textarea:</label>
                    <textarea rows="4" class="form-control rounded-lg border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500" placeholder="Bootstrap structure with Tailwind focus states"></textarea>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="button" class="btn btn-outline-primary flex-1">Bootstrap Outline</button>
                    <button type="button" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-6 rounded-md transition-colors flex-1">Tailwind Purple</button>
                </div>
            </form>
        </div>

        <!-- Status Information -->
        <div class="mt-8 text-center">
            <div class="inline-flex items-center bg-green-100 text-green-800 px-4 py-2 rounded-full">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                CSS Frameworks Successfully Integrated!
            </div>
        </div>
    </div>
</div>
@endsection
