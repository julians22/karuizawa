<footer class=" bg-secondary">
    <div class="container max-w-md py-20 space-y-4">
        <img class="w-full" src="{{ asset('img/brand/logo-01.png') }}" alt="">
        <div class="flex items-center justify-around gap-4">
            <a href="#">
                <img class="size-6" src="{{ asset('img/icons/fb.svg') }}" alt="">
            </a>
            <a href="#">
                <img class="size-6" src="{{ asset('img/icons/ig.svg') }}" alt="">
            </a>
            <a href="#">
                <img class="size-6" src="{{ asset('img/icons/x.svg') }}" alt="">
            </a>
            <a href="#">
                <img class="size-6" src="{{ asset('img/icons/p.svg') }}" alt="">
            </a>
            <a href="#">
                <img class="size-6" src="{{ asset('img/icons/wa.svg') }}" alt="">
            </a>
        </div>
        <div>
            <form class="max-w-md mx-auto">
                <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                <div class="relative">
                    <input type="search" id="default-search" class="block w-full px-4 py-2 text-sm text-gray-900 bg-transparent border border-solid rounded-full border-secondary-50 pe-10" required />
                    <button type="submit" class="absolute inset-y-0 flex items-center end-0 pe-4">
                        <svg class="size-5 text-secondary-50" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="w-full py-8 bg-primary">
        <div class="text-center text-white">© 2024 Karuizawa shirt  |  All Rights Reserved. Flex Japan, Inc.</div>
    </div>
</footer>
