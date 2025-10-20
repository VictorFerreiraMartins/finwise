@auth
    <li class="nav-item">
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('payables.index') }}" class="nav-link {{ request()->routeIs('payables.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-file-invoice-dollar"></i>
            <p>Contas a pagar</p>
        </a>
    </li>
@endauth
