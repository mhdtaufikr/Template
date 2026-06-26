@php
    $isSuperAdmin = \Auth::user()->role === 'Super Admin';
    $coreItems = [
        ['label' => 'Home', 'icon' => 'fas fa-home', 'url' => url('/home'), 'active' => request()->is('home')],
        ['label' => 'Asset', 'icon' => 'fas fa-boxes', 'url' => url('/asset'), 'active' => request()->is('asset*')],
        ['label' => 'Audit', 'icon' => 'fas fa-book-reader', 'url' => url('/audit'), 'active' => request()->is('audit*')],
    ];
    $masterActive = request()->is('asset_category*') || request()->is('cost_center*') || request()->is('department*') || request()->is('location*');
    $configActive = request()->is('dropdown*') || request()->is('rule*') || request()->is('user*');
@endphp

<nav class="topnav navbar navbar-expand-lg shadow-sm navbar-light bg-white asset-topbar" id="topbarNavigation">
    <div class="container-fluid px-3 px-lg-4">
        <a class="navbar-brand d-flex align-items-center me-3" href="{{ url('/home') }}">
            <img src="{{ asset('assets/img/Logo Option 3 (1).png') }}" alt="MKMLogo" height="42" class="me-3">
            <span class="d-none d-md-inline fw-bold text-dark">Asset Management</span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#assetTopbarMenu" aria-controls="assetTopbarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>

        <div class="collapse navbar-collapse" id="assetTopbarMenu">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-lg-center">
                @foreach ($coreItems as $item)
                    <li class="nav-item">
                        <a class="nav-link topbar-link {{ $item['active'] ? 'active' : '' }}" href="{{ $item['url'] }}">
                            <i class="{{ $item['icon'] }} me-2"></i>{{ $item['label'] }}
                        </a>
                    </li>
                @endforeach

                @if($isSuperAdmin)
                    <li class="nav-item dropdown">
                        <a class="nav-link topbar-link dropdown-toggle {{ $masterActive ? 'active' : '' }}" href="#" id="masterDataMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-database me-2"></i>Master Data
                        </a>
                        <ul class="dropdown-menu border-0 shadow-sm" aria-labelledby="masterDataMenu">
                            <li><a class="dropdown-item" href="{{ url('/asset_category') }}">Asset Category</a></li>
                            <li><a class="dropdown-item" href="{{ url('/cost_center') }}">Cost Center</a></li>
                            <li><a class="dropdown-item" href="{{ url('/department') }}">Department</a></li>
                            <li><a class="dropdown-item" href="{{ url('/location') }}">Location</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link topbar-link dropdown-toggle {{ $configActive ? 'active' : '' }}" href="#" id="configurationMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-tools me-2"></i>Configuration
                        </a>
                        <ul class="dropdown-menu border-0 shadow-sm" aria-labelledby="configurationMenu">
                            <li><a class="dropdown-item" href="{{ url('/dropdown') }}">Dropdown</a></li>
                            <li><a class="dropdown-item" href="{{ url('/rule') }}">Rules</a></li>
                            <li><a class="dropdown-item" href="{{ url('/user') }}">User</a></li>
                        </ul>
                    </li>
                @endif
            </ul>

            <ul class="navbar-nav align-items-lg-center">
                <li class="nav-item dropdown no-caret dropdown-user">
                    <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownUserImage" href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="img-fluid" src="{{ asset('assets/img/illustrations/profiles/profile-5.png') }}" />
                    </a>
                    <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownUserImage">
                        <h6 class="dropdown-header d-flex align-items-center">
                            <img class="dropdown-user-img" src="{{ asset('assets/img/illustrations/profiles/profile-5.png') }}" />
                            <div class="dropdown-user-details">
                                <div class="dropdown-user-details-name">{{ auth()->user()->name }}</div>
                                <div class="dropdown-user-details-email">{{ auth()->user()->email }}</div>
                            </div>
                        </h6>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ url('/logout') }}">
                            <div class="dropdown-item-icon"><i data-feather="log-out"></i></div>
                            Logout
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
