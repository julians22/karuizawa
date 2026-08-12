@extends('frontend.layouts.main')

@section('title', __('Abuot Us'))

@section('content')
    <div class="bg-cover bg-no-repeat bg-center w-full h-[50svh]" style="background-image: url('{{ asset('img/about/about-hero.jpg') }}');">
        <div class="flex justify-center items-center h-full text-white text-4xl text-center uppercase tracking-widest container">
            WHAT IS KARUIZAWA?
        </div>
    </div>
    <div class="relative bg-secondary pt-52 md:pt-40 pb-14">
        <div class="container">
            <div class="-top-28 left-1/2 absolute max-md:inset-x-0 -translate-x-1/2 max-md:translate-x-0">
                <div class="before:block after:block before:top-0 after:bottom-0 before:left-1/2 after:left-1/2 before:absolute after:absolute relative bg-white before:bg-primary-50 after:bg-primary-50 mx-auto p-8 lg:p-16 border-primary-50 border-y-4 md:w-[650px] lg:max-w-4xl before:h-2 after:h-2 before:aspect-[40/3] after:aspect-[40/3] before:content-[''] after:content-[''] before:-translate-x-1/2 after:-translate-x-1/2">
                    <p class="text-primary-50 text-2xl text-center">"Karuizawa" is a representative resort in Shinshu, Nagano that has been loved by many people, including cultural figures from Japan and abroad.</p>
                </div>
            </div>
            <p class="font-roboto text-primary-50 text-xl text-justify leading-loose">
                Born in 1940, our factory has manufactured shirts with the notion that the basic of clothing is "comfort" has not changed.
                There are many things involved in comfort, such as mold, fabric, sewing technique, and interlining. High quality and overall balance are necessary for each.
                With this in mind, we bring with sincerity, from a Japanese factory that has been in business for over 80 years to Indonesia.
            </p>
        </div>
    </div>
    {{-- <div class="bg-primary py-14">
        <p class="font-roboto text-white text-xl text-justify leading-loose container">
            Each of us is unique, and that's why it's becoming our promise to constantly developing a collection inspired by modern Japanese culture, craftsmanship with a high quality material that easily customized to suits you, no matter what. Size, Shape, Cut, almost anything for any occasion.
        </p>
    </div> --}}
    <div class="bg-white py-14">
        <div class="gap-14 lg:grid grid-cols-5 container">
            <div class="max-lg:float-right order-2 col-span-2 max-lg:mt-4 max-lg:ml-4 max-lg:w-1/2">
                <img src="{{ asset('img/about/abotu-img.png') }}" alt="">
            </div>
            <div class="float-none space-y-10 col-span-3 font-roboto text-primary-50 text-xl text-justify leading-loose">
                <p>Each of us is unique, and that's why it's becoming our promise to constantly developing a collection inspired by modern Japanese culture, craftsmanship with a high quality material that easily customized to suits you, no matter what. Size, Shape, Cut, almost anything for any occasion.</p>
                <p>
                    Our Japanese high-quality shirt is designed to match typical Asian Fit with options to customize the essential parts of your shirt. A semi-bespoke Japanese made-to-order set will serve you well.
                </p>
            </div>
        </div>
    </div>
@endsection
