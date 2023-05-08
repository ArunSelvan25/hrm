<!-- Sidebar -->
<ul class="navbar-nav bg-white sidebar accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">HRM</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link {{ (request()->is('*/dashboard')) ? 'bg-primary text-white' : '' }}" href="{{ route('get-dashboard',['locale' => app()->getLocale()]) }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>{{ __('sidebar.dashboard') }}</span></a>
    </li>

    @role('admin','admin')
        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('role-permission.view',['locale' => app()->getLocale()]) }}"
                aria-expanded="true" aria-controls="collapseTwo">
                <i class="fas fa-fw fa-unlock"></i>
                <span>{{ __('sidebar.access_management') }}</span>
            </a>
        </li>
    @endrole

    @if(Auth::guard(getGuard())->user()->hasPermissionTo("owner-list",getGuard()))
        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed {{ (request()->is('*house-owner*')) ? 'bg-primary text-white' : '' }}" href="{{ route('house-owner.house-owner',['locale' => app()->getLocale()]) }}"
               aria-expanded="true" aria-controls="collapseTwo">
                <i class="fas fa-fw fa-cog"></i>
                <span>{{ __('sidebar.owner_management') }}</span>
            </a>
        </li>
    @endif

    @if(Auth::guard(getGuard())->user()->hasPermissionTo("property-list",getGuard()))
        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed {{ (request()->is('*property*')) ? 'bg-primary text-white' : '' }}" href="{{ route('property.property',['locale' => app()->getLocale()]) }}"
               aria-expanded="true" aria-controls="collapseTwo">
               <i class="fas fa-solid fa-home"></i>
                <span>{{ __('sidebar.property_management') }}</span>
            </a>
        </li>
    @endif

    @if(Auth::guard(getGuard())->user()->hasPermissionTo("tenant-list",getGuard()))
        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed {{ (request()->is('*tenant*')) ? 'bg-primary text-white' : '' }}" href="{{ route('tenant.tenant',['locale' => app()->getLocale()]) }}"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-solid fa-user-tie"></i>
                <span>{{ __('sidebar.tenant_management') }}</span>
            </a>
        </li>
    @endif

    @if(Auth::guard(getGuard())->user()->hasPermissionTo("user-list",getGuard()))
        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed {{ (request()->is('*user*')) ? 'bg-primary text-white' : '' }}" href="{{ route('user.user',['locale' => app()->getLocale()]) }}"
                aria-expanded="true" aria-controls="collapseTwo">
                <i class="fas fa-duotone fa-users"></i>
                <span>{{ __('sidebar.user_management') }}</span>
            </a>
        </li>
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
