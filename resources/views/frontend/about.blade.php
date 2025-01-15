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
                    <p class="text-2xl text-center text-primary-50">Karuizawa is the name of Japan’s premier resort town located in the east of Nagano Japan, just a little over an hour away from Tokyo by bullet train “Shinkansen”. </p>
                </div>
            </div>
            <p class="text-xl leading-loose text-justify font-roboto text-primary-50">The history of Karuizawa as a resort town began in 1888, when Alexander Croft Shaw, a missionary of the Anglican Communion, took a liking to the cool climate of the area and built here a cottage to spend summers in. Since that time by word of mouth Karuizawa became more and more popular, and many foreign representatives, people working in political or business circles in Tokyo one after another built their cottages in the area. As of now, there are already over 14,000 cottages in the town. This quintessential resort area is also loved by Japan’s imperial family and international celebrities alike. For the Japanese, summering in Karuizawa represents one of their highest status symbols. Japanese political, business and cultural figures have all built holiday cottages here in order to spend their summers in comfort. It was in Karuizawa in 1958 that the current Emperor of Japan and then-Crown Prince first met Michiko, the present Empress of Japan. John Lennon, a founder member of the worldwide famous rock band the BEATLES and his wife, Yoko Ono, used to take their family along to Karuizawa every summer.</p>
        </div>
    </div>
    <div class="py-14 bg-primary">
        <p class="container text-xl leading-loose text-justify text-white font-roboto">
            Because the town was established by a missionary, Karuizawa is truly steeped with the spirit of Christianity, and it is dotted with old churches that were established during the Meiji Period, such as the Karuizawa Shaw Memorial Church and Karuizawa Union Church to name but a few. Since the Showa Period, many chapels were also built all around the town, serving as spaces for religious services unconstrained by sects. As a holy place for devout prayers surrounded by ample riches of nature, Karuizawa is a place of choice for many couples who decide to hold their marriage and exchange their sacred vows here. Since the days long passed, the rich natural environment and cool climate of Karuizawa never ceased to captivate the hearts of many people. Many literary men and artists choose Karuizawa as a place to live and create, and many couples come here to hold a marriage ceremony.
        </p>
    </div>
    <div class="bg-white py-14">
        <div class="container grid-cols-5 lg:grid gap-14">
            <div class="order-2 col-span-2 max-lg:w-1/2 max-lg:float-right max-lg:ml-4 max-lg:mt-4">
                <img src="{{ asset('img/about/abotu-img.png') }}" alt="">
            </div>
            <div class="float-none col-span-3 space-y-10 text-xl leading-loose text-justify font-roboto text-primary-50">
                <p>Karuizawa is also well known as Sports Resort, with golf, tennis, horseback riding in the summer, and curling, skating, skiing and hockey in winter, and it was even selected as the site for curling championship during the 1998 Winter Olympic Games held in Nagano.</p>
                <p>Captivated by the refreshing air and the unique atmosphere of Karuizawa, many men of letters have lived and worked here since the Meiji Period. Up to Taisho Period, there were many literary men who spent summers staying in Karuizawa and writing in hotels or villas, but since the Showa Period, many writers such as Saisei Muro bought their own cottages in the area. One attractive point of Karuizawa is that it became a place for literati to spend time together, as they could visit each other's cottages in spare moments from writing. Today, as both the transportation network and the means of communication have improved so much compared with the days of the past, there are more and more literary men like Yasuo Uchida, who settle down here in Karuizawa.</p>
            </div>
        </div>
    </div>
@endsection
