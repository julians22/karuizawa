@extends('frontend.layouts.main')

@section('title', __('Login'))

@section('content')
 <div class="grid grid-cols-2 max-h-svh h-svh">
    <div class="col-span-1 bg-no-repeat bg-cover" style="background-image: url('{{ asset('img/bg-01.jpg') }}');"></div>
    <div class="col-span-1 bg-no-repeat bg-cover" style="background-image: url('{{ asset('img/bg-02.jpg') }}');">
        <div class="flex items-center justify-center h-full">
            <div class="">
                <img class="mx-auto" src="{{ asset('img/brand/logo-03.png') }}" alt="">
                <div class="mb-5 text-3xl font-bold tracking-widest text-white uppercase mt-7">sign me in as</div>
                @if(isset($errors) && $errors->any())
                    <x-utils.alert type="danger" class="header-message">
                        @foreach($errors->all() as $error)
                            {{ $error }}<br/>
                        @endforeach
                    </x-utils.alert>
                @endif
                <form method="POST" action="{{ route('frontend.auth.login') }}" class="font-roboto text-[#606060] space-y-3">
                    @csrf
                    <div class="relative">
                        <span class="absolute top-0 bottom-0 right-0 flex items-center justify-center w-10 py-1.5 pl-2.5 pr-3 rounded-r-full pointer-events-none bg-primary-50">
                            <svg fill="#ffffff" height="800px" width="800px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                viewBox="0 0 407.437 407.437" xml:space="preserve">
                                <polygon points="386.258,91.567 203.718,273.512 21.179,91.567 0,112.815 203.718,315.87 407.437,112.815 "/>
                            </svg>
                        </span>
                        <select id="countries" class="bg-white rounded-full focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 before:bg-blue-400">
                            <option value="Karuizawa’s Store Crew ">Karuizawa’s Store Crew </option>
                            <option value="user">User</option>
                        </select>
                    </div>
                    <input type="email" name="email" class="bg-white rounded-full focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('email') }}" placeholder="Username" required />
                    <input type="password" name="password" class="bg-white rounded-full focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('password') }}" placeholder="Password" required />
                    <div class="flex justify-center w-full !mt-16">
                        <button type="submit" class="flex items-center justify-center gap-2 mx-auto text-lg tracking-widest text-center text-white uppercase font-josefin">
                            <span class="self-center mt-1">Login</span>
                            <img class="inline-block" src="{{ asset('img/icons/arrw-ck-right.png') }}" alt="">
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
 </div>
@endsection
