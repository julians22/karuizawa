<div class="sidebar sidebar-dark sidebar-fixed border-end" id="sidebar">
    <div class="sidebar-brand">
        <svg class="sidebar-brand-full d-none d-md-block" width="118" height="46" alt="CoreUI Logo">
            <use xlink:href="{{ asset('img/brand/coreui.svg#full') }}"></use>
        </svg>
        <svg class="sidebar-brand-minimized d-block d-md-none" width="46" height="46" alt="CoreUI Logo">
            <use xlink:href="{{ asset('img/brand/coreui.svg#signet') }}"></use>
        </svg>
    </div><!--sidebar-brand-->

    <ul class="sidebar-nav">
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
                    :href="route('admin.store.index')"
                    :active="activeClass(Route::is('admin.store.*'), 'active')"
                    icon="nav-icon cil-speedometer"
                    :text="__('Store Management')" />
            </li>

            <li class="nav-title">@lang('System')</li>

            <li class="nav-group {{ activeClass(Route::is('admin.auth.user.*') || Route::is('admin.auth.role.*'), 'open show') }}">
                <x-utils.link
                    href="#"
                    icon="nav-icon cil-user"
                    class="nav-link nav-group-toggle"
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
            <li class="nav-group" aria-expanded="false">
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
