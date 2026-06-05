<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPKOS - Sistem Informasi Kos</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <!-- DataTables -->
    <link rel="stylesheet"
        href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">

    <link rel="stylesheet"
        href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
</head>

<body class="bg-slate-50 font-sans antialiased min-h-screen flex flex-col">

    <nav class="bg-white border-b border-slate-100 sticky top-0 z-50 shadow-sm w-full">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}"
                class="text-2xl font-black tracking-wider text-blue-600">
                SIPKOS
            </a>

            <div class="flex items-center space-x-8 font-semibold text-sm text-slate-600">
                <a href="{{ route('home') }}"
                    class="{{ Request::is('/') ? 'text-blue-600 border-b-2 border-blue-600 pb-5 pt-5 mt-1' : 'hover:text-blue-500' }}">
                    Home
                </a>

                <a href="{{ route('my.bookings') }}"
                    class="{{ Request::is('my-bookings') ? 'text-blue-600 border-b-2 border-blue-600 pb-5 pt-5 mt-1' : 'hover:text-blue-500' }}">
                    My Bookings
                </a>

                <a href="{{ route('admin') }}"
                    class="{{ Request::is('admin*') ? 'text-blue-600 border-b-2 border-blue-600 pb-5 pt-5 mt-1' : 'hover:text-blue-500' }}">
                    Admin
                </a>
            </div>
        </div>
    </nav>

    <main class="w-full max-w-7xl mx-auto px-6 py-8 flex-1">
        @yield('content')
    </main>

    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    @yield('scripts')

</body>
</html>