@extends('layouts.auth-split')

@section('title', __('auth.login_or_register'))
@section('image-key', 'central_portal')

@section('content')
    <livewire:onboarding.central-portal />
@endsection
