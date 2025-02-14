@extends('frontend.layouts.main')

@section('title', __('Home'))

@section('content')
    <div class="bg-no-repeat bg-center bg-cover h-[50svh] w-full" style="background-image: url('{{ asset('img/about/about-hero.jpg') }}');">
        <div class="container flex items-center justify-center h-full text-4xl tracking-widest text-center text-white uppercase">
            WHAT IS KARUIZAWA?
        </div>
    </div>
    <div class="relative pt-52 md:pt-40 pb-14 bg-secondary">
        <div class="container">
            <div class="absolute -translate-x-1/2 -top-28 left-1/2 max-md:inset-x-0 max-md:translate-x-0">
                <div class="md:w-[650px] lg:max-w-4xl p-8 lg:p-16 mx-auto bg-white border-y-4 border-primary-50 relative
                    after:content-[''] after:block after:aspect-[40/3] after:h-2 after:bg-primary-50 after:absolute after:bottom-0 after:left-1/2 after:-translate-x-1/2
                    before:content-[''] before:block before:aspect-[40/3] before:h-2 before:bg-primary-50 before:absolute before:top-0 before:left-1/2 before:-translate-x-1/2
                ">
                    <p class="text-2xl text-center text-primary-50">"Karuizawa" is a representative resort in Shinshu, Nagano that has been loved by many people, including cultural figures from Japan and abroad.</p>
                </div>
            </div>
            <p class="text-xl leading-loose text-justify font-roboto text-primary-50">
                Born in 1940, our factory has manufactured shirts with the notion that the basic of clothing is "comfort" has not changed.
                There are many things involved in comfort, such as mold, fabric, sewing technique, and interlining. High quality and overall balance are necessary for each.
                With this in mind, we bring with sincerity, from a Japanese factory that has been in business for over 80 years to Indonesia.
            </p>
        </div>
    </div>
    {{-- <div class="py-14 bg-primary">
        <p class="container text-xl leading-loose text-justify text-white font-roboto">
            Each of us is unique, and that's why it's becoming our promise to constantly developing a collection inspired by modern Japanese culture, craftsmanship with a high quality material that easily customized to suits you, no matter what. Size, Shape, Cut, almost anything for any occasion.
        </p>
    </div> --}}
    <div class="bg-white py-14">
        <div class="container grid-cols-5 lg:grid gap-14">
            <div class="order-2 col-span-2 max-lg:w-1/2 max-lg:float-right max-lg:ml-4 max-lg:mt-4">
                <img src="{{ asset('img/about/abotu-img.png') }}" alt="">
            </div>
            <div class="float-none col-span-3 space-y-10 text-xl leading-loose text-justify font-roboto text-primary-50">
                <p>Each of us is unique, and that's why it's becoming our promise to constantly developing a collection inspired by modern Japanese culture, craftsmanship with a high quality material that easily customized to suits you, no matter what. Size, Shape, Cut, almost anything for any occasion.</p>
                <p>
                    Our Japanese high-quality shirt is designed to match typical Asian Fit with options to customize the essential parts of your shirt. A semi-bespoke Japanese made-to-order set will serve you well.
                </p>
            </div>
        </div>
    </div>
@endsection
