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
        <a class="nav-link {{ (request()->is('*admin/dashboard')) ? 'bg-primary text-white' : '' }}" href="{{ route('admin.get-dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    @if(Auth::guard(getGuard())->user() && Auth::guard(getGuard())->user()->hasPermissionTo('role-list',getGuard()))
        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('admin.role-permission') }}"
                aria-expanded="true" aria-controls="collapseTwo">
                <i class="fas fa-fw fa-unlock"></i>
                <span>Access Management</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed {{ (request()->is('*admin/house-owner*')) ? 'bg-primary text-white' : '' }}" href="{{ route('admin.house-owner') }}"
               aria-expanded="true" aria-controls="collapseTwo">
                <i class="fas fa-fw fa-cog"></i>
                <span>Owner Management</span>
            </a>
        </li>
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed {{ (request()->is('*admin/property*')) ? 'bg-primary text-white' : '' }}" href="{{ route('admin.property') }}"
           aria-expanded="true" aria-controls="collapseTwo">
           <i class="fas fa-solid fa-home"></i>
            <span>Property Management</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed {{ (request()->is('*admin/tenant*')) ? 'bg-primary text-white' : '' }}" href="{{ route('admin.tenant') }}"
           aria-expanded="true" aria-controls="collapseTwo">
           <i class="fas fa-solid fa-user-tie"></i>
            <span>Tenant Management</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed {{ (request()->is('*admin/user*')) ? 'bg-primary text-white' : '' }}" href="{{ route('admin.user') }}"
           aria-expanded="true" aria-controls="collapseTwo">
           <i class="fas fa-duotone fa-users"></i>
            <span>User Management</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
