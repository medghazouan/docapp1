<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'DocApp') }}</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/layoutstyle.css') }}">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('images/menaraHoldingLogo.png') }}" alt="Logo" onerror="this.src='data:image/svg+xml;charset=UTF-8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'40\' height=\'40\' viewBox=\'0 0 40 40\'><rect width=\'40\' height=\'40\' fill=\'%23a49672\'/><text x=\'50%\' y=\'50%\' font-size=\'20\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%23171717\'>MH</text></svg>'">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto main-nav">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link {{ Request::routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                                    <i class="fas fa-tachometer-alt me-2"></i>{{ __('Tableau de bord') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::routeIs('demandes*') ? 'active' : '' }}" href="{{ route('demandes.index') }}">
                                    <i class="fas fa-file-alt me-2"></i>{{ __('Demandes') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::routeIs('documents*') ? 'active' : '' }}" href="{{ route('documents.index') }}">
                                    <i class="fas fa-folder-open me-2"></i>{{ __('Documents') }}
                                </a>
                            </li>
                            @if(Auth::user()->role == 'admin')
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::routeIs('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                                        <i class="fas fa-users me-2"></i>{{ __('Utilisateurs') }}
                                    </a>
                                </li>
                            @endif
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">
                                        <i class="fas fa-sign-in-alt me-2"></i>{{ __('Connexion') }}
                                    </a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">
                                        <i class="fas fa-user-plus me-2"></i>{{ __('Inscription') }}
                                    </a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item">
                                <a class="nav-link notification-link" href="{{ route('notifications.index') }}">
                                    <i class="fas fa-bell"></i>
                                    @if(Auth::user()->notifications()->where('read', false)->count() > 0)
                                        <span class="notification-badge">{{ Auth::user()->notifications()->where('read', false)->count() }}</span>
                                    @endif
                                </a>
                            </li>
                            <li class="nav-item dropdown user-dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <div class="user-avatar">
                                        {{ substr(Auth::user()->nom, 0, 1) }}
                                    </div>
                                    <span class="d-none d-md-inline">{{ Auth::user()->nom }}</span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('users.edit', Auth::user()->idUtilisateur) }}">
                                        <i class="fas fa-user"></i> {{ __('Mon Profil') }}
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-cog"></i> {{ __('Paramètres') }}
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt"></i> {{ __('Déconnexion') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Main content area for your different pages -->
                <div class="row">
                    <!-- This is where your page content will go -->
                    @yield('content')
                </div>
            </div>
        </main>
        
        <footer class="footer mt-auto">
            <div class="container">
                Website | © Copyright 2025 Holding Menara | Système de Demande de Documents en Ligne
            </div>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @yield('scripts')
    @stack('scripts')
    </div>
</body>
</html>