@extends('frontend.layouts.main')

@section('title', __('Home'))

@section('content')
    <div class="home_hero xl:h-[88svh]">
        <div id="hero_splide" class="splide" role="group">
            <div class="splide__track">
                  <div class="flex splide__list">
                      <div class="relative splide__slide">
                        <img class="object-cover w-full h-full" src="{{ asset('img/home/bg-hero-1.jpg') }}" alt="">
                        <div class="absolute inset-0 z-10">
                            <div class="flex flex-col items-center justify-center h-full gap-2 md:gap-4">
                                <div class="tracking-widest text-center text-white uppercase md:text-5xl xl:text-7xl">
                                    Grand <br>
                                    Opening <br>
                                    discount
                                </div>
                                <div class="text-xs tracking-wider text-center text-white md:text-sm xl:text-lg font-roboto">
                                    Enjoy discount up to 50% <br>
                                    on PIK Avenue Brach
                                </div>
                                <div>
                                    <a href="#" class="flex items-center gap-2 pl-4 pr-2 pt-2 pb-1.5 border border-white rounded-full">
                                        <span class="text-xs md:text-xl tracking-widest text-white uppercase mt-0.5">See More</span>
                                        <span><img src="{{ asset('img/icons/arrw-ck-right.png') }}" alt=""></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                      </div>
                      <div class="splide__slide">
                        <img class="object-cover w-full h-full" src="{{ asset('img/home/img-hero.jpg') }}" alt="">
                      </div>
                  </div>
            </div>
          </div>
    </div>
    <div class="home_section_one">
        <div class="h-36 bg-secondary-50 lg:h-60"></div>
        <div class="relative">
            <div class="absolute inset-0 grid" aria-hidden="true">
                <div class="bg-secondary-50 -mt-0.5"></div>
                <div class="bg-primary -mb-0.5"></div>
            </div>
            <div class="container isolate">
                <div class="mb-10 text-xl font-bold tracking-widest text-center text-white uppercase lg:text-3xl">Our Collections</div>
                <div id="collection_splide" class="splide" role="group">
                    <div class="splide__track">
                          <div class="flex splide__list">
                                @for ($i = 0; $i < 6; $i++)
                                    <div class="splide__slide aspect-[9/11]">
                                        <div class="relative w-full h-full bg-center bg-no-repeat bg-cover bg-secondary" style="background-image: url('{{ asset('img/home/collection-0'. ($i + 1) . '.jpg') }}');">
                                            <div class="absolute inset-x-0 -translate-y-1/2 top-1/2">
                                                <div class="mx-auto w-fit">
                                                    <div class="font-medium tracking-widest text-white uppercase max-md:text-sm lg:text-xl">
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
                                                    <div class="h-1 mt-2 bg-white rounded md:mt-4"></div>
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
        <div class="h-20 lg:h-40 bg-primary">
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
        new Splide( '#hero_splide', {
            perPage: 1,
            type    : 'loop',
            autoplay: true,
            arrows: false,
            pagination: false,
        }).mount();

        const splide = new Splide( '#collection_splide', {
            perPage: 3,
            arrows: false,
            pagination: true,
            gap: '1rem',
            autoHeight: true,
            }).mount();
    </script>
@endpush
