<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="theme-light bg-page">
    <div id="app">
        <nav class="bg-header section">
            <div class="container mx-auto">
                <div class="flex justify-between items-center py-1">

                    <!-- Left Side Of Navbar -->
                    <h1>
                        <a class="navbar-brand" href="{{ url('/projects') }}">
                            <img src="/images/logo.svg" alt="Birdboard">
                        </a>
                    </h1>

                    <!-- Right Side Of Navbar -->
                    <div>
                        <div class="flex items-center ml-auto">
                            <!-- Authentication Links -->
                            @guest
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                @if (Route::has('register'))
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                @endif
                            @else
                                <theme-switcher></theme-switcher>

                                <dropdown align="right" width="200px">
                                    <template v-slot:trigger>
                                        <button 
                                            class="flex items-center text-default no-underline text-sm">
                                            <img width="35"
                                                src="{{ gravatar_url(auth()->user()->email) }}" 
                                                alt="{{ auth()->user()->name }}"
                                                class="rounded-full mr-3">

                                            {{ auth()->user()->name }}
                                        </button>
                                    </template>

                                    <form id="logout-form" method="POST" action="/logout">
                                        @csrf
                                        <button type="submit" class="dropdown-menu-link w-full text-left">
                                            Logout
                                        </button>
                                    </form>

                                </dropdown>


                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <div class="section py-6">
            @yield('content')
        </div>
    </div>
</body>
</html>
