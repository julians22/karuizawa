@extends('frontend.layouts.main')

@section('title', __('Dashboard'))

@section('content')
    <div class="grid grid-cols-8 min-h-[86svh]">
        <div class="col-span-2">
            <div class="flex flex-col items-center min-h-[86svh] py-20 bg-right-bottom bg-no-repeat bg-cover px-7" style="background-image: url('{{ asset('img/bg-02.jpg') }}');">
                <div class="border-4 border-white border-solid rounded-full size-36 bg-primary-50"></div>
                <div class="mt-4 text-xl tracking-widest text-center text-white uppercase">
                    Hi, <br>
                    {{ $logged_in_user->name }}
                </div>
                <div class="mt-0.5 text-sm text-center text-white">Karuizawa’s Store Crew </div>
                <div class="mt-10">
                    <a class="text-sm tracking-widest text-white uppercase" href="{{ route('frontend.user.account') }}">Edit Profile</a>
                    <div class="h-0.5 w-full bg-white opacity-40 mt-3 mb-4"></div>
                </div>
                <form method="POST" action="{{ route('frontend.auth.logout') }}">
                    @csrf
                    <button type="submit" class="text-sm tracking-widest text-white uppercase">Log out</button>
                </form>
            </div>
        </div>
        <div class="col-span-6 bg-no-repeat bg-cover" style="background-image: url('{{ asset('img/bg-03.jpg') }}'); background-position: bottom; background-size: 100% 110%;">
            <div class="flex items-center justify-center h-full">
                <div class="max-w-md p-9 bg-secondary-50 bg-opacity-85">
                    <div class="mx-4 text-xl tracking-widest text-center text-white uppercase">
                        WELCOME TO
                        KARUIZAWA’S CUSTOMER
                        ORDER PLATFORM
                    </div>
                    <div class="h-0.5 w-4/5 mx-auto bg-white opacity-40 mt-3 mb-4"></div>
                    <div class="text-sm text-center text-white">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laore</div>
                </div>
            </div>
        </div>
    </div>
@endsection
