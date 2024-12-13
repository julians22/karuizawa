@extends('frontend.layouts.main')

@section('title', __('Home'))

@section('content')
    <div class="home_hero h-[88svh]">
        <img class="object-cover w-full h-full" src="{{ asset('img/home/img-hero.jpg') }}" alt="">
    </div>
    <div class="home_section_one">
        <div class="bg-secondary-50 h-60"></div>
        <div class="relative">
            <div class="absolute inset-0 grid" aria-hidden="true">
                <div class="bg-secondary-50"></div>
                <div class="bg-primary"></div>
            </div>
            <div class="container isolate">
                <div class="mb-10 text-3xl font-bold tracking-widest text-center text-white uppercase">Our Collections</div>
                {{-- <div class="grid grid-cols-3 gap-6">
                    <div class="bg-gray-400 aspect-[9/12]"></div>
                    <div class="bg-gray-400 aspect-[9/12]"></div>
                    <div class="bg-gray-400 aspect-[9/12]"></div>
                </div> --}}
                <div class="splide" role="group" aria-label="Splide Basic HTML Example">
                    <div class="splide__track">
                          <div class="splide__list">
                                @for ($i = 0; $i < 6; $i++)
                                    <div class="splide__slide aspect-[9/11]">
                                        <div class="relative w-full h-full bg-center bg-no-repeat bg-cover bg-secondary" style="background-image: url('{{ asset('img/home/collection-0'. ($i + 1) . '.jpg') }}');">
                                            <div class="absolute inset-x-0 -translate-y-1/2 top-1/2">
                                                <div class="mx-auto w-fit">
                                                    <div class="text-xl font-medium tracking-widest text-white uppercase">
                                                        @switch($i)
                                                            @case(0)
                                                                shirt
                                                                @break
                                                            @case(1)
                                                                pants
                                                                @break
                                                            @case(2)
                                                                jacket
                                                                @break

                                                            @default
                                                                default
                                                        @endswitch
                                                    </div>
                                                    <div class="h-1 mt-4 bg-white rounded"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                          </div>
                    </div>
                  </div>
            </div>
        </div>
        <div class="h-40 bg-primary">
            <div class="container relative h-full pointer-events-none">
                <img class="absolute bottom-0 right-10 w-[2.2%]" src="{{ asset('img/vertical-line.png') }}" alt="">
            </div>
        </div>
    </div>
@endsection

@push('after-styles')
<style>
    .splide .splide__pagination {
        bottom: -2rem!important;
    }

    .splide .splide__pagination .splide__pagination__page {
        height: 7px;
        width: 45px;
        border-radius: 20px;
        background: #cccccc75;
    }

    .splide .splide__pagination .splide__pagination__page.is-active{
        background: #fff;
        transform: scale(1);
    }
</style>
@endpush

@push('after-scripts')
<script>
    new Splide( '.splide', {
        perPage: 3,
        arrows: false,
        pagination: true,
        gap: '1rem',
        autoHeight: true,
        }).mount();
</script>
@endpush
