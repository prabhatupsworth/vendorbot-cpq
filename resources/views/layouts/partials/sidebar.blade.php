<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">


        <div id="sidebar-menu" class="sidebar-menu">
            {{-- <ul>
                <li class="clinicdropdown">
                    <a href="{{ route('profile') }}">
                        <img src="{{ auth()->user()->profile_image
                            ? asset(auth()->user()->profile_image)
                            : asset('template/assets/img/profiles/avatar-14.jpg') }}"
                            class="img-fluid" alt="Profile">
                        <div class="user-names">
                            <h5>{{ auth()->user()->name }}</h5>
                            <h6>{{ ucwords(str_replace('_', ' ', $authRole)) }}</h6>
                        </div>
                    </a>
                </li>
            </ul> --}}

            <ul>

                {{-- DASHBOARD --}}

                <li>
                     <h6 class="submenu-hdr">
                            main
                        </h6>

                    <ul>

                        <li>

                            <a href="{{ route('dashboard') }}"
                                class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">

                                <i class="ti ti-layout-2"></i>

                                <span>Dashboard</span>

                            </a>

                        </li>
                        {{-- PROJECT --}}
                        @if (userCanModule('projects'))

                            @if (userCan('projects.view'))

                                @if (moduleEnabled('Invoice') && Route::has('projects.index'))
                                    <li>

                                        <a href="{{ route('projects.index') }}"
                                            class="{{ request()->routeIs('projects.*') ? 'active' : '' }}">

                                            <i class="ti ti-briefcase"></i>

                                            <span>Projects</span>

                                        </a>

                                    </li>
                                @endif

                            @endif
                        @endif

                    </ul>

                </li>


                {{-- DRAFTS --}}
                @if (userCanModule('email'))
                    <li>

                        <h6 class="submenu-hdr">
                            Operations
                        </h6>

                        <ul>

                            @if (userCan('email.view'))

                                @if (moduleEnabled('Draft') && Route::has('draft.index'))
                                    <li>

                                        <a href="{{ route('draft.index') }}"
                                            class="{{ request()->routeIs('draft.*') ? 'active' : '' }}">

                                            <i class="ti ti-mail"></i>

                                            <span>Emails</span>

                                        </a>

                                    </li>
                                @endif

                            @endif

                            @if (userCanModule('suppliers'))

                                @if (userCan('suppliers.view'))
                                    <li>

                                        <a href="{{ route('suppliers.index') }}"
                                            class="{{ request()->routeIs('suppliers.index') ? 'active' : '' }}">
                                            <i class="ti ti-truck"></i>
                                            <span>Suppliers</span>
                                        </a>

                                    </li>
                                @endif
                            @endif

                            {{-- PRODUCTS --}}

                            @if (userCanModule('products'))
                                @if (userCan('products.view'))
                                    <li>

                                        <a href="{{ route('products.index') }}"
                                            class="{{ request()->routeIs('products.index') ? 'active' : '' }}">
                                            <i class="ti ti-package"></i>
                                            <span>Products</span>

                                        </a>

                                    </li>
                                @endif

                            @endif

                            {{-- COUPONS --}}

                            @if (userCanModule('coupons'))

                                @if (userCan('coupons.view'))
                                    <li>

                                        <a href="{{ route('coupon.index') }}"
                                            class="{{ request()->routeIs('coupon.index') ? 'active' : '' }}">
                                            <i class="ti ti-ticket"></i>
                                            <span>Coupons</span>

                                        </a>

                                    </li>
                                @endif
                            @endif

                        </ul>

                    </li>
                @endif


                {{-- USER --}}

                @if (userCanModule('users'))

                    <li>

                        <h6 class="submenu-hdr">
                            Access Control
                        </h6>

                        <ul>

                            @if (userCan('users.view'))
                                <li>
                                    <a href="{{ route('users.index') }}"
                                        class="{{ request()->routeIs('users.index') ? 'active' : '' }}">
                                        <i class="ti ti-users"></i>
                                        <span>Users</span>
                                    </a>
                                </li>
                            @endif

                            @if (auth()->user()->hasRole('super_admin'))
                                <li>
                                    <a href="{{ route('roles.index') }}"
                                        class="{{ request()->routeIs('roles.*') ? 'active' : '' }}">
                                        <i class="ti ti-shield-lock"></i>
                                        <span>Roles & Permissions</span>
                                    </a>
                                </li>
                            @endif

                        </ul>

                    </li>

                @endif
                {{-- SETTINGS --}}
                @if (userCanModule('crm_integrations') || userCanModule('invoice_management'))
                    <li>

                        <h6 class="submenu-hdr">
                            Settings
                        </h6>

                        <ul>

                            <li class="submenu">

                                <a href="javascript:void(0);"
                                    class="{{ request()->routeIs('settings.*') ? 'subdrop active' : '' }}">

                                    <i class="ti ti-settings"></i>

                                    <span>System Settings</span>

                                    <span class="menu-arrow"></span>

                                </a>

                                <ul
                                    style="{{ request()->routeIs('settings.*') ? 'display:block;' : 'display:none;' }}">

                                    @if (userCanModule('crm_integrations'))
                                        @if (userCan('crm_integrations.view'))

                                            @if (moduleEnabled('Pipedrive') && Route::has('settings.pipedrive.index'))
                                                <li>

                                                    <a href="{{ route('settings.pipedrive.index') }}"
                                                        class="{{ request()->routeIs('settings.pipedrive.index') ? 'active' : '' }}">

                                                        CRM Integrations

                                                    </a>

                                                </li>
                                            @endif

                                        @endif

                                    @endif


                                    @if (userCanModule('invoice_management'))
                                        @if (userCan('invoice_management.view'))

                                            @if (moduleEnabled('Invoice') && Route::has('settings.invoice.lexware.index'))
                                                <li>

                                                    <a href="{{ route('settings.invoice.lexware.index') }}"
                                                        class="{{ request()->routeIs('settings.invoice.lexware.index') ? 'active' : '' }}">

                                                        Invoice Management

                                                    </a>

                                                </li>
                                            @endif

                                        @endif
                                    @endif

                                </ul>

                            </li>

                        </ul>

                    </li>
                @endif
            </ul>

        </div>
    </div>
</div>
