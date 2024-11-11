<header class="header header-sticky bg-light p-0 mb-4">
    <div class="container-fluid border-bottom px-4">
      <ul class="header-nav d-none d-lg-flex">
        <li class="nav-item px-3"><a class="nav-link" href="{{ route('frontend.index') }}">@lang('Home')</a></li>

        @if(config('boilerplate.locale.status') && count(config('boilerplate.locale.languages')) > 1)
            <li class="nav-item dropdown">
                <x-utils.link
                    :text="__(getLocaleName(app()->getLocale()))"
                    class="nav-link dropdown-toggle"
                    id="navbarDropdownLanguageLink"
                    data-coreui-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false" />

                @include('includes.partials.lang')
            </li>
        @endif
      </ul>
      <ul class="header-nav ms-auto">
        <li class="nav-item dropdown">
            <x-utils.link class="nav-link" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <x-slot name="text">
                    <div class="avatar">
                        <img class="avatar-img" src="{{ $logged_in_user->avatar }}" alt="{{ $logged_in_user->email ?? '' }}">
                    </div>
                </x-slot>
            </x-utils.link>

            <div class="dropdown-menu dropdown-menu-right pt-0">
                <div class="dropdown-header bg-light py-2">
                    <strong>@lang('Account')</strong>
                </div>

                <x-utils.link
                    class="dropdown-item"
                    icon="c-icon mr-2 cil-account-logout"
                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <x-slot name="text">
                        @lang('Logout')
                        <x-forms.post :action="route('frontend.auth.logout')" id="logout-form" class="d-none" />
                    </x-slot>
                </x-utils.link>
            </div>
        </li>
      </ul>
    </div>
    <div class="container-fluid px-4">
        @include('backend.includes.partials.breadcrumbs')

        <div class="subheader-nav mfe-2">
            @yield('breadcrumb-links')
        </div>
    </div>
  </header>

{{-- <header class="header header-light header-fixed">
    <button class="header-toggler class-toggler d-lg-none mfe-auto" type="button" data-target="#sidebar" data-class="c-sidebar-show">
        <i class="icon icon-lg cil-menu"></i>
    </button>

    <a class="header-brand d-lg-none" href="#">
        <svg width="118" height="46" alt="CoreUI Logo">
            <use xlink:href="{{ asset('img/brand/coreui.svg#full') }}"></use>
        </svg>
    </a>

    <button class="header-toggler class-toggler mfs-3 d-md-down-none" type="button" data-target="#sidebar" data-class="sidebar-lg-show" responsive="true">
        <i class="icon icon-lg cil-menu"></i>
    </button>

    <ul class="header-nav d-md-down-none">
        <li class="nav-item px-3"><a class="nav-link" href="{{ route('frontend.index') }}">@lang('Home')</a></li>

        @if(config('boilerplate.locale.status') && count(config('boilerplate.locale.languages')) > 1)
            <li class="nav-item dropdown">
                <x-utils.link
                    :text="__(getLocaleName(app()->getLocale()))"
                    class="nav-link dropdown-toggle"
                    id="navbarDropdownLanguageLink"
                    data-coreui-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false" />

                @include('includes.partials.lang')
            </li>
        @endif
    </ul>

    <ul class="header-nav ml-auto mr-4">
        <li class="nav-item dropdown">
            <x-utils.link class="nav-link" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <x-slot name="text">
                    <div class="avatar">
                        <img class="avatar-img" src="{{ $logged_in_user->avatar }}" alt="{{ $logged_in_user->email ?? '' }}">
                    </div>
                </x-slot>
            </x-utils.link>

            <div class="dropdown-menu dropdown-menu-right pt-0">
                <div class="dropdown-header bg-light py-2">
                    <strong>@lang('Account')</strong>
                </div>

                <x-utils.link
                    class="dropdown-item"
                    icon="c-icon mr-2 cil-account-logout"
                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <x-slot name="text">
                        @lang('Logout')
                        <x-forms.post :action="route('frontend.auth.logout')" id="logout-form" class="d-none" />
                    </x-slot>
                </x-utils.link>
            </div>
        </li>
    </ul>

    <div class="container-fluid px-4 px-3">
        @include('backend.includes.partials.breadcrumbs')

        <div class="subheader-nav mfe-2">
            @yield('breadcrumb-links')
        </div>
    </div><!--c-subheader-->
</header> --}}
