<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pengguna - SIPKOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center p-6">

    <div class="w-full max-w-5xl grid lg:grid-cols-2 bg-white rounded-[2rem] overflow-hidden shadow-2xl">
        <div class="hidden lg:flex bg-gradient-to-br from-blue-600 to-cyan-400 p-10 text-white flex-col justify-between">
            <div>
                <h1 class="text-3xl font-black tracking-wider">SIPKOS</h1>
                <p class="mt-3 text-white/90">Login Pengguna untuk mulai mencari kos impianmu.</p>
            </div>

            <div>
                <h2 class="text-4xl font-black leading-tight mb-4">Cari Kos dengan Mudah, Cepat, dan Nyaman</h2>
                <p class="text-white/90">
                    Temukan kos berdasarkan lokasi, harga, fasilitas, maps terdekat, dan booking secara online.
                </p>
            </div>

            <div class="text-sm text-white/80">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-bold underline">Daftar di sini</a>
            </div>
        </div>

        <div class="p-8 md:p-12">
            <a href="{{ route('landing') }}" class="text-sm text-slate-500 hover:text-blue-600 font-semibold">&larr; Kembali</a>

            <h2 class="text-3xl font-black text-slate-800 mt-4">Login Pengguna</h2>
            <p class="text-slate-500 mt-2 mb-8">Masuk untuk mulai mencari dan booking kos.</p>

            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.user.submit') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Password</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 outline-none">
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3.5 rounded-xl font-black transition">
                    Masuk sebagai Pengguna
                </button>
            </form>

            <div class="mt-6 text-sm text-slate-500 text-center">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-blue-600 font-bold">Daftar sekarang</a>
            </div>

            <div class="mt-4 text-sm text-slate-500 text-center">
                Login admin?
                <a href="{{ route('login.admin') }}" class="text-slate-700 font-bold">Masuk ke halaman admin</a>
            </div>
        </div>
    </div>

</body>
</html>