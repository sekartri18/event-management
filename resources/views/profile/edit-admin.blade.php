@extends('layouts.admin-layout')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ __('Pengaturan Profil') }}
</h2>
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <div class="max-w-xl">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <div class="max-w-xl">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <div class="max-w-xl">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection
