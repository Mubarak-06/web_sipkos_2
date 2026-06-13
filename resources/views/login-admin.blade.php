<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - SIPKOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center p-6">

    <div class="w-full max-w-md bg-white rounded-[2rem] shadow-2xl p-8">
        <a href="{{ route('landing') }}" class="text-sm text-slate-500 hover:text-blue-600 font-semibold">&larr; Kembali</a>

        <div class="mt-4 mb-8">
            <h1 class="text-3xl font-black text-slate-800">Login Admin</h1>
            <p class="text-slate-500 mt-2">Masuk untuk mengelola data kos dan dashboard admin SIPKOS.</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.admin.submit') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Email Admin</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Password</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 outline-none">
            </div>

            <button type="submit"
                class="w-full bg-slate-900 hover:bg-slate-800 text-white py-3.5 rounded-xl font-black transition">
                Masuk sebagai Admin
            </button>
        </form>

        <div class="mt-6 text-sm text-center text-slate-500">
            Login pengguna?
            <a href="{{ route('login.user') }}" class="text-blue-600 font-bold">Masuk di sini</a>
        </div>
    </div>

</body>
</html>