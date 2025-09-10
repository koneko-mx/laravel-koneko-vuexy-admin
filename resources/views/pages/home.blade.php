@extends('vuexy-admin::layouts.vuexy.layoutMaster')

@section('vendor-style')
    @vite('vendor/koneko/laravel-koneko-vuexy-admin/resources/scss/pages/quick-access-card.scss')
@endsection

@section('content')
    @livewire('vuexy-admin::menu-access-cards')
@endsection
