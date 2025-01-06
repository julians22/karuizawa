<nav class="bg-secondary">
    <div class="container py-7">
        <div class="grid items-end grid-cols-7 gap-10">
            <div class="col-span-2">
                <a href="{{ route('frontend.index') }}">
                    <img src="{{ asset('img/brand/logo-01.png') }}" alt="">
                </a>
            </div>
            <div class="col-span-5 -mb-4">
                @if ($logged_in_user && Route::is('frontend.user.*'))
                    <ul class="flex gap-[5%] text-primary-50 font-medium uppercase w-full tracking-widest">
                        <li>
                            <div class="relative inline-block dropdown group">
                                <button class="font-medium uppercase text-primary-50 group-hover:before:bg-transparent {{ Route::is('frontend.user.rtw') || Route::is('frontend.user.semi-custom')  ? 'active' : '' }}">
                                    Shop
                                </button>
                                <div class="absolute z-10 hidden group-hover:block top-[1.3rem] inset-x-0">
                                    <div class="w-0 h-0 border-l-[1rem] border-y-8 border-y-transparent border-l-primary-50"></div>
                                </div>
                                <ul class="absolute z-10 hidden shadow-xl dropdown-menu group-hover:block bg-primary-50 top-7">
                                  <li class=""><a class="block px-4 py-2 text-white whitespace-no-wrap hover:text-primary-200" href="{{ route('frontend.user.rtw') }}">RTW</a></li>
                                  <li><div class="h-[1px] w-[88%] bg-white opacity-75 mx-auto"></div></li>
                                  <li class=""><a class="block px-4 py-2 text-white whitespace-pre hover:text-primary-200" href="{{ route('frontend.user.semi-custom') }}">semi custom</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="{{ Route::is('frontend.user.booking') ? 'active' : '' }}"><a href="{{ route('frontend.user.booking') }}" class="py-1">CUSTOMER BOOKING</a></li>
                    </ul>
                @else
                    <ul class="flex gap-4 lg:gap-[5%] text-primary-50 font-medium uppercase w-full justify-center tracking-wider text-sm lg:text-base">
                        <li><a href="{{ route('frontend.user.rtw') }}">Ready To Wear</a></li>
                        <li><a href="{{ route('frontend.about-us') }}">About Us</a></li>
                        <li class="self-center"><div class="h-[1px] w-20 lg:w-40 bg-primary-50"></div></li>
                        @if ($logged_in_user)
                        <li>
                            <div class="relative inline-block dropdown group">
                                <button class="font-medium uppercase text-primary-50">
                                    {{ $logged_in_user->name }}
                                </button>
                                <div class="absolute inset-x-0 z-10 hidden group-hover:block top-[1.3rem]">
                                    <div class="w-0 h-0 border-l-[1rem] border-y-8 border-y-transparent border-l-primary-50"></div>
                                </div>
                                <ul class="absolute hidden dropdown-menu group-hover:block bg-primary-50 top-7">
                                  <li class=""><a class="block px-4 py-2 text-white whitespace-no-wrap hover:text-primary-200" href="{{ route('frontend.user.dashboard') }}">Profile</a></li>
                                  <li><div class="h-[1px] w-[88%] bg-white opacity-75 mx-auto"></div></li>
                                  <li class="">
                                    <form method="POST" action="{{ route('frontend.auth.logout') }}">
                                        @csrf
                                        <button type="submit" class="block px-4 py-2 text-white uppercase whitespace-pre hover:text-primary-200">Log out</button>
                                    </form>
                                  </li>
                                </ul>
                            </div>
                        </li>
                        @else
                        <li><a class="flex" href="{{ route('frontend.auth.login') }}"><img class="self-auto size-4 " class="inline-block" src="{{ asset('img/icons/icon-lock.svg') }}" alt=""> Login</a></li>
                        @endif
                    </ul>
                @endif
            </div>
        </div>
    </div>
</nav>

