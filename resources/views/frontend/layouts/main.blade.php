<!doctype html>
<html lang="{{ htmlLang() }}" @langrtl dir="rtl" @endlangrtl>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ appName() }} | @yield('title')</title>
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
    <div id="app">
        @if (!Route::is('frontend.auth.login'))
            @include('frontend.includes.main.nav')
        @endif
        {{-- @include('includes.partials.messages') --}}

        <main>
            @yield('content')
        </main>

        @if (!Route::is('frontend.auth.login'))
            @include('frontend.includes.main.footer')
        @endif
    </div><!--app-->

    @stack('before-scripts')
    <script src="{{ mix('js/manifest.js') }}"></script>
    <script src="{{ mix('js/vendor.js') }}"></script>
    <script src="{{ mix('js/frontend.js') }}"></script>
    <livewire:scripts />
    @stack('after-scripts')
</body>
</html>
