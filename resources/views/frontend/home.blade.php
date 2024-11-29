@extends('frontend.layouts.main')

@section('title', __('Home'))

@section('content')
    <div class="home_hero">
        <img class="w-full" src="{{ asset('img/home/img-hero-100.jpg') }}" alt="">
    </div>
    <div class="home_section_one">
        <div class="bg-secondary-50 h-[30rem]"></div>
        <div class="bg-primary h-80"></div>
    </div>
@endsection
