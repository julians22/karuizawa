@extends('frontend.layouts.main')

@section('title', __('Dashboard'))

@section('content')
    <x-frontend.sidebar>
        <div class="h-full bg-no-repeat bg-cover" style="background-image: url('{{ asset('img/bg-03.jpg') }}'); background-position: bottom; background-size: 100% 110%;">
            <div class="flex items-center justify-center h-full">
                <div class="max-w-md p-9 bg-secondary-50 bg-opacity-85">
                    <div class="mx-4 tracking-widest text-center text-white uppercase lg:text-xl">
                        WELCOME TO
                        KARUIZAWA’S CUSTOMER
                        ORDER PLATFORM
                    </div>
                    <div class="h-0.5 w-4/5 mx-auto bg-white opacity-40 mt-3 mb-4"></div>
                    <div class="text-sm text-center text-white">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laore</div>
                </div>
            </div>
        </div>
    </x-frontend.sidebar>
@endsection
