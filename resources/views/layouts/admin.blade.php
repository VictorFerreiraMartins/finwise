<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'Laravel'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                @auth
                    <li class="nav-item d-none d-md-flex align-items-center mr-2 text-muted">
                        <span class="nav-link">{{ auth()->user()->name }}</span>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link text-danger">
                                <i class="fas fa-sign-out-alt mr-1"></i> Sair
                            </button>
                        </form>
                    </li>
                @endauth
                @stack('navbar-right')
            </ul>
        </nav>

        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="{{ url('/') }}" class="brand-link">
                <span class="brand-text font-weight-light">{{ config('app.name', 'Laravel') }}</span>
            </a>

            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        @include('layouts.partials.sidebar')
                        @stack('sidebar')
                    </ul>
                </nav>
            </div>
        </aside>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            @hasSection('page_header')
                                @yield('page_header')
                            @else
                                <h1 class="m-0">@yield('title', config('app.name', 'Laravel'))</h1>
                            @endif
                        </div>
                        <div class="col-sm-6">
                            @hasSection('breadcrumb')
                                <ol class="breadcrumb float-sm-right">
                                    @yield('breadcrumb')
                                </ol>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    @includeWhen(session('status') || $errors->any(), 'layouts.partials.flash')
                    @yield('content')
                </div>
            </section>
        </div>

        <footer class="main-footer">
            <div class="float-right d-none d-sm-inline">
                @yield('footer-right')
            </div>
            <strong>&copy; {{ now()->year }} {{ config('app.name', 'Laravel') }}.</strong> All rights reserved.
        </footer>
    </div>

    @stack('scripts')
</body>

</html>
