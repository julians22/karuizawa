<!doctype html>
<html lang="{{ htmlLang() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ appName() }} | @yield('title')</title>
    <meta name="description" content="@yield('meta_description', appName())">
    <meta name="author" content="@yield('meta_author', 'Designcub3')">
    @yield('meta')

    @stack('before-styles')
    <link href="{{ mix('css/backend.css') }}" rel="stylesheet">
    @stack('after-styles')

    @livewireStyles
</head>
<body>
    @include('backend.includes.sidebar')

    <div class="wrapper d-flex flex-column min-vh-100 bg-light">
        @include('backend.includes.header')

        <div class="c-body">
            <main class="c-main">
                <div class="container-fluid">
                    <div class="fade-in">
                        @include('includes.partials.read-only')
                        @include('includes.partials.logged-in-as')
                        @include('includes.partials.announcements')
                        @include('includes.partials.messages')
                        @yield('content')
                    </div><!--fade-in-->
                </div><!--container-fluid-->
            </main>
        </div><!--c-body-->

        @include('backend.includes.footer')
    </div><!--c-wrapper-->

    @stack('before-scripts')
    <script src="{{ mix('js/manifest.js') }}"></script>
    <script src="{{ mix('js/vendor.js') }}"></script>
    <script src="{{ mix('js/backend.js') }}"></script>

    <script src="{{ asset('js/coreui.bundle.js') }}"></script>
    @stack('after-scripts')
    @livewireScripts

    <script>

        // var mySidebar = document.querySelector('#sidebar')
        // var sidebar = new coreui.Sidebar(mySidebar)

    </script>
</body>
</html>
