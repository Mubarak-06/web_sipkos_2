<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPKOS - Sistem Informasi Kos</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
</head>
<body class="bg-slate-50 font-sans antialiased min-h-screen flex flex-col">

    <nav class="bg-white border-b border-slate-100 sticky top-0 z-50 shadow-sm w-full">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="
                @auth
                    {{ auth()->user()->role === 'admin' ? route('admin') : route('home') }}
                @else
                    {{ route('landing') }}
                @endauth
            " class="text-2xl font-black tracking-wider text-blue-600">
                SIPKOS
            </a>

            <div class="flex items-center space-x-6 font-semibold text-sm text-slate-600">
                @auth
                    @if(auth()->user()->role === 'user')
                        <a href="{{ route('home') }}" class="hover:text-blue-500">Home</a>
                        <a href="{{ route('my.bookings') }}" class="hover:text-blue-500">My Bookings</a>
                    @endif

                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin') }}" class="hover:text-blue-500">Dashboard Admin</a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login.user') }}" class="hover:text-blue-500">Login User</a>
                    <a href="{{ route('login.admin') }}" class="hover:text-blue-500">Login Admin</a>
                    <a href="{{ route('register') }}"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition">
                        Daftar
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="w-full max-w-7xl mx-auto px-6 py-8 flex-1">
        @yield('content')
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    @yield('scripts')
</body>
</html>