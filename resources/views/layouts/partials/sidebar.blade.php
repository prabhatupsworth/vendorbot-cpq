<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="clinicdropdown">
                    <a href="{{ route('profile') }}">
                        <img src="{{ auth()->user()->profile_image
                            ? asset( auth()->user()->profile_image)
                            : asset('template/assets/img/profiles/avatar-14.jpg') }}"
                            class="img-fluid" alt="Profile">
                        <div class="user-names">
                            <h5>{{ auth()->user()->name }}</h5>
                            <h6>{{ ucwords(str_replace('_', ' ', $authRole)) }}</h6>
                        </div>
                    </a>
                </li>
            </ul>
            <ul>

                {{-- DASHBOARD --}}

                <li>

                    <h6 class="submenu-hdr">
                        Main Menu
                    </h6>

                    <ul>

                        <li>

                            <a href="{{ route('dashboard') }}"
                                class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">

                                <i class="ti ti-layout-2"></i>

                                <span>Dashboard</span>

                            </a>

                        </li>

                    </ul>

                </li>

                {{-- PROJECT MANAGEMENT --}}

                <li>

                    <h6 class="submenu-hdr">
                        Project Management
                    </h6>

                    <ul>

                        @can('projects.view')

                            @if (moduleEnabled('Invoice') && Route::has('projects.index'))
                                <li>

                                    <a href="{{ route('projects.index') }}"
                                        class="{{ request()->routeIs('projects.*') ? 'active' : '' }}">

                                        <i class="ti ti-briefcase"></i>

                                        <span>Projects</span>

                                    </a>

                                </li>
                            @endif

                        @endcan

                    </ul>

                </li>

                {{-- SUPPLIERS --}}
                @can('suppliers.view')
                    <li>

                        <h6 class="submenu-hdr">
                            Suppliers
                        </h6>

                        <ul>

                            <li class="submenu">

                                <a href="javascript:void(0);"
                                    class="{{ request()->routeIs('suppliers.*') ? 'subdrop active' : '' }}">

                                    <i class="ti ti-truck"></i>

                                    <span>Supplier Management</span>

                                    <span class="menu-arrow"></span>

                                </a>

                                <ul style="{{ request()->routeIs('suppliers.*') ? 'display:block;' : 'display:none;' }}">

                                    <li>

                                        <a href="{{ route('suppliers.index') }}"
                                            class="{{ request()->routeIs('suppliers.index') ? 'active' : '' }}">

                                            Suppliers

                                        </a>

                                    </li>

                                    {{-- <li>

                                    <a href="{{ route('suppliers.create') }}"
                                        class="{{ request()->routeIs('suppliers.create') ? 'active' : '' }}">

                                        Create Supplier

                                    </a>

                                </li> --}}

                                    {{-- <li>

                                    <a href="{{ route('suppliers.import') }}"
                                        class="{{ request()->routeIs('suppliers.import') ? 'active' : '' }}">

                                        Import Suppliers

                                    </a>

                                </li> --}}
                                </ul>

                            </li>

                        </ul>

                    </li>
                @endcan

                {{-- PRODUCTS --}}

                <li>

                    <h6 class="submenu-hdr">
                        Products
                    </h6>

                    <ul>

                        <li class="submenu">

                            <a href="javascript:void(0);"
                                class="{{ request()->routeIs('products.*') ? 'subdrop active' : '' }}">

                                <i class="ti ti-package"></i>

                                <span>Product Management</span>

                                <span class="menu-arrow"></span>

                            </a>

                            <ul style="{{ request()->routeIs('products.*') ? 'display:block;' : 'display:none;' }}">

                                @can('products.view')
                                    <li>

                                        <a href="{{ route('products.index') }}"
                                            class="{{ request()->routeIs('products.index') ? 'active' : '' }}">

                                            Products

                                        </a>

                                    </li>
                                @endcan

                                @can('products.create')
                                    <li>

                                        <a href="{{ route('products.create') }}"
                                            class="{{ request()->routeIs('products.create') ? 'active' : '' }}">

                                            Create Product

                                        </a>

                                    </li>
                                @endcan

                            </ul>

                        </li>

                    </ul>

                </li>

                {{-- COUPONS --}}
                @can('coupons.view')
                    <li>

                        <h6 class="submenu-hdr">
                            Coupons
                        </h6>

                        <ul>
                            <li class="submenu">

                                <a href="javascript:void(0);"
                                    class="{{ request()->routeIs('coupon.*') ? 'subdrop active' : '' }}">

                                    <i class="ti ti-ticket"></i>

                                    <span>Coupon Management</span>

                                    <span class="menu-arrow"></span>

                                </a>

                                <ul style="{{ request()->routeIs('coupon.*') ? 'display:block;' : 'display:none;' }}">

                                    <li>

                                        <a href="{{ route('coupon.index') }}"
                                            class="{{ request()->routeIs('coupon.index') ? 'active' : '' }}">

                                            Coupons

                                        </a>

                                    </li>

                                    <li>

                                        <a href="{{ route('coupon.create') }}"
                                            class="{{ request()->routeIs('coupon.create') ? 'active' : '' }}">

                                            Create Coupon

                                        </a>

                                    </li>

                                </ul>

                            </li>
                        </ul>

                    </li>
                @endcan

                {{-- USER MANAGEMENT --}}

                <li>

                    <h6 class="submenu-hdr">
                        User Management
                    </h6>

                    <ul>

                        @can('users.view')
                            <li>

                                <a href="{{ route('users.index') }}"
                                    class="{{ request()->routeIs('users.*') ? 'active' : '' }}">

                                    <i class="ti ti-users"></i>

                                    <span>Users</span>

                                </a>

                            </li>
                        @endcan

                        @can('roles.view')
                            <li>

                                <a href="{{ route('roles.index') }}"
                                    class="{{ request()->routeIs('roles.*') ? 'active' : '' }}">

                                    <i class="ti ti-navigation-cog"></i>

                                    <span>Roles & Permissions</span>

                                </a>

                            </li>
                        @endcan

                    </ul>

                </li>

                {{-- SETTINGS --}}

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

                            <ul style="{{ request()->routeIs('settings.*') ? 'display:block;' : 'display:none;' }}">

                                @can('pipedrive.view')

                                    @if (moduleEnabled('Pipedrive') && Route::has('settings.pipedrive.index'))
                                        <li>

                                            <a href="{{ route('settings.pipedrive.index') }}"
                                                class="{{ request()->routeIs('settings.pipedrive.index') ? 'active' : '' }}">

                                                Pipedrive

                                            </a>

                                        </li>
                                    @endif

                                @endcan

                                @can('lexware.view')

                                    @if (moduleEnabled('Invoice') && Route::has('settings.invoice.lexware.index'))
                                        <li>

                                            <a href="{{ route('settings.invoice.lexware.index') }}"
                                                class="{{ request()->routeIs('settings.invoice.lexware.index') ? 'active' : '' }}">

                                                Lexware

                                            </a>

                                        </li>
                                    @endif

                                @endcan

                            </ul>

                        </li>

                    </ul>

                </li>

            </ul>

        </div>
    </div>
</div>
