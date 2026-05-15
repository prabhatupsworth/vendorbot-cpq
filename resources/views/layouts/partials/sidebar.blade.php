<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="clinicdropdown">
                    <a href="{{ route('profile') }}">
                        <img src="{{ auth()->user()->profile_image
                            ? asset('storage/' . auth()->user()->profile_image)
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
                <li>
                    <h6 class="submenu-hdr">Main Menu</h6>
                    <ul>

                        <li>
                            <a href="{{ route('dashboard') }}"
                                class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="ti ti-layout-2"></i><span>Dashboard</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <h6 class="submenu-hdr">User Management</h6>
                    <ul>
                        @can('users.view')
                            <li><a href="{{ route('users.index') }}"
                                    class="{{ request()->routeIs('users.*') ? 'active' : '' }}"><i
                                        class="ti ti-users"></i><span>Manage
                                        Users</span></a></li>
                        @endcan

                        @can('roles.view')
                            <li><a href="{{ route('roles.index') }}"
                                    class="{{ request()->routeIs('roles.*') ? 'active' : '' }}"><i
                                        class="ti ti-navigation-cog"></i><span>Roles &
                                        Permissions</span></a></li>
                        @endcan
                    </ul>
                </li>
                <li>
                    <h6 class="submenu-hdr">PROJECTS</h6>
                    <ul>
                        @can('projects.view')
                            @if (moduleEnabled('Invoice') && Route::has('projects.index'))
                                <li><a href="{{ route('projects.index') }}"
                                        class="{{ request()->routeIs('projects.*') ? 'active' : '' }}"><i
                                            class="ti ti-briefcase"></i><span>Projects</span></a>
                                </li>
                            @endif
                        @endcan
                    </ul>
                </li>

                <li>
                    <h6 class="submenu-hdr">Products</h6>

                    <ul>

                        <li class="submenu">

                            <a href="javascript:void(0);"
                                class="{{ request()->routeIs('products.*') ? 'subdrop active' : '' }}">

                                <i class="ti ti-package"></i>

                                <span>Product Management</span>

                                <span class="menu-arrow"></span>

                            </a>

                            <ul style="{{ request()->routeIs('products.*') ? 'display: block;' : 'display: none;' }}">

                                {{-- Products --}}

                                @can('products.view')
                                    <li>

                                        <a href="{{ route('products.index') }}"
                                            class="{{ request()->routeIs('products.*') ? 'active' : '' }}">

                                            Products

                                        </a>

                                    </li>
                                @endcan
                                @can('products.create')
                                    <li>

                                        <a href="{{ route('products.create') }}"
                                            class="{{ request()->routeIs('products.*') ? 'active' : '' }}">

                                            New Product

                                        </a>

                                    </li>
                                @endcan
                            </ul>

                        </li>

                    </ul>

                </li>
                <li>
                    <h6 class="submenu-hdr">Settings</h6>
                    <ul>

                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="{{ request()->routeIs('settings.*') ? 'subdrop active' : '' }}">
                                <i class="ti ti-world-cog"></i><span>Website Settings</span><span
                                    class="menu-arrow"></span>
                            </a>
                            <ul style="display: none;">
                                @can('pipedrive.view')
                                    @if (moduleEnabled('Pipedrive') && Route::has('settings.pipedrive.index'))
                                        <li><a href="{{ route('settings.pipedrive.index') }}"
                                                class="{{ request()->routeIs('settings.pipedrive.index') ? 'active' : '' }}">Pipedrive</a>
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
