<div class="sidebar sidebar-dark sidebar-fixed border-end" id="sidebar">
    <div class="sidebar-brand">
        <img class="sidebar-brand-full d-none d-md-block" width="160" height="46" alt="Brand Logo" src="{{ asset('img/brand/logo-01.png') }}" style="filter: invert(1);">
        <i,g class="sidebar-brand-minimized d-block d-md-none" width="30" height="46" alt="Brand Logo" src="{{ asset('img/brand/logo-02.png') }}"/>
    </div><!--sidebar-brand-->

    <ul class="sidebar-nav" data-coreui="navigation" data-simplebar="init">
        <li class="nav-item">
            <x-utils.link
                class="nav-link"
                :href="route('admin.dashboard')"
                :active="activeClass(Route::is('admin.dashboard'), 'active')"
                icon="nav-icon cil-speedometer"
                :text="__('Dashboard')" />
        </li>

        @if (
            $logged_in_user->hasAllAccess() ||
            (
                $logged_in_user->can('admin.access.user.list') ||
                $logged_in_user->can('admin.access.user.deactivate') ||
                $logged_in_user->can('admin.access.user.reactivate') ||
                $logged_in_user->can('admin.access.user.clear-session') ||
                $logged_in_user->can('admin.access.user.impersonate') ||
                $logged_in_user->can('admin.access.user.change-password')
            )
        )
            <li class="nav-title">@lang('Master Data')</li>

            {{-- Customer --}}
            <li class="nav-item">
                <x-utils.link
                    class="nav-link"
                    :href="route('admin.customer.index')"
                    :active="activeClass(Route::is('admin.customer.*'), 'active')"
                    icon="nav-icon fas fa-users"
                    :text="__('Customer Management')" />

            <li class="nav-item">
                <x-utils.link
                    class="nav-link"
                    :href="route('admin.product.index')"
                    :active="activeClass(Route::is('admin.product.*'), 'active')"
                    icon="nav-icon fas fa-box"
                    :text="__('Product Management')" />
            </li>
            <li class="nav-item">
                <x-utils.link
                    class="nav-link"
                    :href="route('admin.product-category.index')"
                    :active="activeClass(Route::is('admin.product-category*'), 'active')"
                    icon="nav-icon fas fa-box"
                    :text="__('Category Management')" />
            </li>

            <li class="nav-item">
                <x-utils.link
                    class="nav-link"
                    :href="route('admin.store.index')"
                    :active="activeClass(Route::is('admin.store.*'), 'active')"
                    icon="nav-icon cil-speedometer"
                    :text="__('Store Management')" />
            </li>

            <li class="nav-item">
                <x-utils.link
                    class="nav-link"
                    :href="route('admin.promo.index')"
                    :active="activeClass(Route::is('admin.promo.*'), 'active')"
                    icon="nav-icon cil-list"
                    :text="__('Promo Management')" />
            </li>

            {{-- Transaction / Order Menu --}}
            <li class="nav-title">@lang('Transaction')</li>

            {{-- All Orders Menu --}}
            <li class="nav-item">
                <x-utils.link
                    class="nav-link"
                    :href="route('admin.order.index')"
                    :active="activeClass((Route::is('admin.order.index') || Route::is('admin.order.show')), 'active')"
                    icon="nav-icon fas fa-shopping-cart"
                    :text="__('Order Management')" />

            </li>

            {{-- Semi Custom Order Menu --}}
            <li class="nav-item">
                <x-utils.link
                    class="nav-link"
                    :href="route('admin.order.semi-custom.index')"
                    :active="activeClass(Route::is('admin.semi-custom-order.*'), 'active')"
                    icon="nav-icon fas fa-pencil-ruler"
                    :text="__('Semi Custom Order')" />
            </li>

            {{-- Ready To Wear Order Menu --}}
            <li class="nav-item">
                <x-utils.link
                    class="nav-link"
                    :href="route('admin.order.ready-to-wear.index')"
                    :active="activeClass(Route::is('admin.ready-to-wear-order.*'), 'active')"
                    icon="nav-icon fas fa-vest"
                    :text="__('Ready To Wear Order')" />
            </li>

            {{-- Reports --}}
            <li class="nav-title">@lang('Reports')</li>

            <li class="nav-item">
                <x-utils.link
                    class="nav-link"
                    :href="route('admin.report.index')"
                    :active="activeClass(Route::is('admin.report.index.*'), 'active')"
                    icon="nav-icon fas fa-chart-line"
                    :text="__('Report')" />
            </li>

            <li class="nav-item">
                <x-utils.link
                    class="nav-link"
                    :href="route('admin.report.performance')"
                    :active="activeClass(Route::is('admin.report.performance.*'), 'active')"
                    icon="nav-icon fas fa-chart-line"
                    :text="__('Performance Report')" />
            </li>

            <li class="nav-title">@lang('System')</li>

            @if ($logged_in_user->hasAllAccess())

                <li class="nav-item">
                    <x-utils.link
                        class="nav-link"
                        :href="route('admin.system-information.index')"
                        :active="activeClass(Route::is('admin.system-information.*'), 'active')"
                        icon="nav-icon cil-info"
                        :text="__('System Information')" />
                </li>
            @endif

            <li
                aria-expanded="false"
                class="nav-group {{ activeClass(Route::is('admin.auth.user.*') || Route::is('admin.auth.role.*'), 'open show') }}">
                <x-utils.link
                    href="#"
                    icon="nav-icon cil-user"
                    class="nav-link nav-group-toggle"
                    data-coreui-toggle="dropdown"
                    :text="__('Access')" />

                <ul class="nav-group-items">
                    @if (
                        $logged_in_user->hasAllAccess() ||
                        (
                            $logged_in_user->can('admin.access.user.list') ||
                            $logged_in_user->can('admin.access.user.deactivate') ||
                            $logged_in_user->can('admin.access.user.reactivate') ||
                            $logged_in_user->can('admin.access.user.clear-session') ||
                            $logged_in_user->can('admin.access.user.impersonate') ||
                            $logged_in_user->can('admin.access.user.change-password')
                        )
                    )
                        <li class="nav-item">
                            <x-utils.link
                                :href="route('admin.auth.user.index')"
                                class="nav-link"
                                :text="__('User Management')"
                                :active="activeClass(Route::is('admin.auth.user.*'), 'active')" />
                        </li>
                    @endif

                    @if ($logged_in_user->hasAllAccess())
                        <li class="nav-item">
                            <x-utils.link
                                :href="route('admin.auth.role.index')"
                                class="nav-link"
                                :text="__('Role Management')"
                                :active="activeClass(Route::is('admin.auth.role.*'), 'active')" />
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if ($logged_in_user->hasAllAccess())
            <li
                class="nav-group"
                aria-expanded="false">
                <x-utils.link
                    href="#"
                    icon="nav-icon cil-list"
                    class="nav-link nav-group-toggle"
                    :text="__('Logs')" />

                <ul class="nav-group-items">
                    <li class="nav-item">
                        <x-utils.link
                            :href="route('log-viewer::dashboard')"
                            class="nav-link"
                            :text="__('Dashboard')" />
                    </li>
                    <li class="nav-item">
                        <x-utils.link
                            :href="route('log-viewer::logs.list')"
                            class="nav-link"
                            :text="__('Logs')" />
                    </li>
                </ul>
            </li>
        @endif
    </ul>

    <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
</div><!--sidebar-->
