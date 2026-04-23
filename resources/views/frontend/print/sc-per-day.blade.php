<!doctype html>
<html lang="{{ htmlLang() }}" @langrtl dir="rtl" @endlangrtl>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Print Semi Custom Per Day - {{ $date }}</title>
    <meta name="description" content="@yield('meta_description', appName())">
    <meta name="author" content="@yield('meta_author', 'F_Skn')">
    @yield('meta')

    @stack('before-styles')
    <link href="{{ asset('fonts/base/stylesheet.css') }}" rel="stylesheet" />
    <link href="{{ mix('css/styles.css') }}" rel="stylesheet">
    <livewire:styles />
    @stack('after-styles')
</head>
<body>
    <div id="app" class="space-y-4">
        @if ($dataSemiCustom == null || $dataSemiCustom->isEmpty() || $dataSemiCustomOuter == null || $dataSemiCustomOuter->isEmpty())
            <div class="flex flex-col justify-center items-center h-screen">
                <h1 class="font-bold text-gray-500 text-2xl text-center">Tidak ada data transaksi semi custom pada tanggal {{ Carbon\Carbon::parse($date)->format('d F Y') }}</h1>
                <a href="{{ route('frontend.user.booking') }}" class="bg-primary-50 mt-10 p-2 font-bold text-white text-xl uppercase">Kembali</a>
            </div>
        @endif
        @foreach ($dataSemiCustom as $key => $value )
        <div>
            <print-semi-custom
            :data_config="{{ $dataConfig }}"
            :data_semi_custom="{{ JSON_encode($value) }}"
            ></print-semi-custom>
        </div>
        @endforeach
        @foreach ($dataSemiCustomOuter as $key => $value )
        <div>
            <print-semi-custom-outer
            :data_config="{{ $dataConfigOuter }}"
            :data_semi_custom_outer="{{ JSON_encode($value) }}"
            ></print-semi-custom-outer>
        </div>
        @endforeach

    </div><!--app-->

    @stack('before-scripts')
    <script src="{{ mix('js/manifest.js') }}"></script>
    <script src="{{ mix('js/vendor.js') }}"></script>
    <script src="{{ mix('js/frontend.js') }}"></script>
    <livewire:scripts />
    @stack('after-scripts')
</body>
</html>
