<div class="grid grid-cols-8 lg:min-h-[86svh]">
        <div class="col-span-2">
            <div class="flex flex-col items-center lg:min-h-[86svh] sidebar" style="background-image: url('{{ asset('img/bg-02.jpg') }}');">
                <div class="overflow-hidden border-4 border-white border-solid rounded-full size-32 lg:size-36 bg-primary-50">
                    <img class="w-full h-full" src="{{ $logged_in_user->avatar }}" alt="">
                </div>
                <div class="mt-4 tracking-widest text-center text-white uppercase lg:text-xl">
                    Hi, <br>
                    {{ $logged_in_user->name }}
                </div>
                <div class="mt-0.5 text-sm text-center text-white">Karuizawa’s Store Crew </div>
                <div class="mt-10">
                    <a class="text-sm tracking-widest text-white uppercase" href="{{ route('frontend.user.edit-profile') }}">Edit Profile</a>
                    <div class="h-0.5 w-full bg-white opacity-40 mt-3 mb-4"></div>
                </div>
                <form method="POST" action="{{ route('frontend.auth.logout') }}">
                    @csrf
                    <button type="submit" class="text-sm tracking-widest text-white uppercase">Log out</button>
                </form>
            </div>
        </div>
        <div class="col-span-6">
            {{ $slot }}
        </div>
    </div>
