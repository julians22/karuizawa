<nav class="z-50 relative bg-secondary">
    <div class="py-7 container">
        <div class="items-end gap-10 grid grid-cols-7">
            <div class="max-md:self-center col-span-7 md:col-span-2">
                <a href="{{ route('frontend.index') }}">
                    <img class="max-md:mx-auto max-md:w-1/2" src="{{ asset('img/brand/logo-01.png') }}" alt="">
                </a>
            </div>
            <div class="col-span-7 md:col-span-5 -mb-4">
                @if ($logged_in_user && Route::is('frontend.user.*'))
                    <ul class="flex gap-[5%] w-full font-medium text-primary-50 uppercase tracking-widest">
                        <li>
                            <div class="group inline-block relative dropdown">
                                <button class="font-medium uppercase text-primary-50 group-hover:before:bg-transparent {{ Route::is('frontend.user.rtw') || Route::is('frontend.user.semi-custom')  ? 'active' : '' }}">
                                    Shop
                                </button>
                                <div class="hidden group-hover:block top-[1.3rem] z-10 absolute inset-x-0">
                                    <div class="border-y-8 border-y-transparent border-l-[1rem] border-l-primary-50 w-0 h-0"></div>
                                </div>
                                <ul style="width: 220px;" class="hidden group-hover:block top-7 left-0 z-10 absolute bg-primary-50 shadow-xl dropdown-menu">
                                  <li class=""><a class="block px-4 py-2 text-white hover:text-primary-200 whitespace-no-wrap" href="{{ route('frontend.user.rtw') }}">RTW</a></li>
                                  <li><div class="bg-white opacity-75 mx-auto w-[88%] h-[1px]"></div></li>
                                  <li class=""><a class="block px-4 py-2 text-white hover:text-primary-200 whitespace-pre" href="{{ route('frontend.user.semi-custom') }}">semi custom</a></li>
                                  <li><div class="bg-white opacity-75 mx-auto w-[88%] h-[1px]"></div></li>
                                  <li class=""><a class="block px-4 py-2 text-white hover:text-primary-200 whitespace-pre" href="{{ route('frontend.user.semi-custom-outer') }}">semi custom outer</a></li>
                                  <li><div class="bg-white opacity-75 mx-auto w-[88%] h-[1px]"></div></li>
                                  <li class=""><a class="block px-4 py-2 text-white hover:text-primary-200 whitespace-no-wrap" href="{{ route('frontend.user.rtw') }}?brand=kizoku">Kizoku Parfume</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="{{ Route::is('frontend.user.booking') ? 'active' : '' }}"><a href="{{ route('frontend.user.booking') }}" class="py-1">CUSTOMER BOOKING</a></li>
                    </ul>
                @else
                    <ul class="flex justify-center gap-3 md:gap-4 lg:gap-[5%] w-full font-medium text-primary-50 text-xs md:text-sm lg:text-base uppercase tracking-normal md:tracking-wider">
                        <li>
                            <li>
                                <div class="group inline-block relative dropdown">
                                    <button class="font-medium uppercase text-primary-50 group-hover:before:bg-transparent {{ Route::is('frontend.user.rtw') || Route::is('frontend.user.semi-custom')  ? 'active' : '' }}">
                                        Shop
                                    </button>
                                    <div class="hidden group-hover:block top-[1.3rem] z-10 absolute inset-x-0">
                                        <div class="border-y-8 border-y-transparent border-l-[1rem] border-l-primary-50 w-0 h-0"></div>
                                    </div>
                                    <ul class="hidden group-hover:block top-7 z-10 absolute bg-primary-50 shadow-xl dropdown-menu">
                                        <li class=""><a class="block px-4 py-2 text-white hover:text-primary-200 whitespace-no-wrap" href="{{ route('frontend.user.rtw') }}">RTW</a></li>
                                        <li><div class="bg-white opacity-75 mx-auto w-[88%] h-[1px]"></div></li>
                                        <li class=""><a class="block px-4 py-2 text-white hover:text-primary-200 whitespace-pre" href="{{ route('frontend.user.semi-custom') }}">semi custom</a></li>
                                        <li><div class="bg-white opacity-75 mx-auto w-[88%] h-[1px]"></div></li>
                                        <li class=""><a class="block px-4 py-2 text-white hover:text-primary-200 whitespace-pre" href="{{ route('frontend.user.semi-custom-outer') }}">semi custom outer</a></li>
                                        <li><div class="bg-white opacity-75 mx-auto w-[88%] h-[1px]"></div></li>
                                        <li class=""><a class="block px-4 py-2 text-white hover:text-primary-200 whitespace-no-wrap" href="{{ route('frontend.user.rtw') }}?brand=kizoku">Kizoku Parfume</a></li>
                                    </ul>
                                </div>
                            </li>
                        </li>
                        @auth
                            <li class="{{ Route::is('frontend.user.booking') ? 'active' : '' }}"><a href="{{ route('frontend.user.booking') }}" class="py-1">CUSTOMER BOOKING</a></li>
                        @endauth
                        <li><a href="{{ route('frontend.about-us') }}">About Us</a></li>
                        <li class="self-center"><div class="bg-primary-50 w-10 md:w-20 lg:w-40 h-[1px]"></div></li>
                        @if ($logged_in_user)
                        <li>
                            <div class="group inline-block relative dropdown">
                                <button class="font-medium text-primary-50 uppercase">
                                    {{ $logged_in_user->name }}
                                </button>
                                <div class="hidden group-hover:block top-[1.3rem] z-10 absolute inset-x-0">
                                    <div class="border-y-8 border-y-transparent border-l-[1rem] border-l-primary-50 w-0 h-0"></div>
                                </div>
                                <ul class="hidden group-hover:block top-7 absolute bg-primary-50 dropdown-menu">
                                @if ($logged_in_user->type == 'crew')
                                    <li class=""><a class="block px-4 py-2 text-white hover:text-primary-200 whitespace-no-wrap" href="{{ route('crew.dashboard') }}">Profile</a></li>
                                    @else
                                    <li class=""><a class="block px-4 py-2 text-white hover:text-primary-200 whitespace-no-wrap" href="{{ route('frontend.user.dashboard') }}">Profile</a></li>
                                @endif
                                  <li><div class="bg-white opacity-75 mx-auto md:w-[88%] h-[1px]"></div></li>
                                  <li class="">
                                    <form method="POST" action="{{ route('frontend.auth.logout') }}">
                                        @csrf
                                        <button type="submit" class="block px-4 py-2 text-white hover:text-primary-200 uppercase whitespace-pre">Log out</button>
                                    </form>
                                  </li>
                                </ul>
                            </div>
                        </li>
                        @else
                        <li><a class="flex" href="{{ route('frontend.auth.login') }}"><img class="self-auto size-3 md:size-4" class="inline-block" src="{{ asset('img/icons/icon-lock.svg') }}" alt=""> Login</a></li>
                        @endif
                    </ul>
                @endif
            </div>
        </div>
    </div>
</nav>

