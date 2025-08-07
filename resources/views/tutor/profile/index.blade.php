@extends('layouts.app')

@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@push('head')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endpush

@section('navbar')
    @include('partials.tutor-navbar')
@endsection

@section('content')
<div class="profile-container py-5">
    <div class="container">
        <div class="row">
            @include('tutor.profile.components.profile-header')

            <!-- Main Content -->
            <div class="col-lg-8">
                @include('tutor.profile.components.personal-info')
                @include('tutor.profile.components.about-section')
                @include('tutor.profile.components.skills-section')
                @include('tutor.profile.components.education-section')
                @include('tutor.profile.components.languages-section')
                @include('tutor.profile.components.portfolio-section')
                @include('tutor.profile.components.video-section')
                @include('tutor.profile.components.certifications-section')
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                @include('tutor.profile.components.availability-section')
                @include('tutor.profile.components.profile-stats')
            </div>
        </div>
    </div>
</div>

@include('tutor.profile.components.profile-styles')

@include('tutor.profile.components.profile-scripts')
@endsection
