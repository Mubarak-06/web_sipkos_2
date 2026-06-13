<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPKOS - Temukan Kos Terbaik</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800">

    <section class="min-h-screen bg-gradient-to-br from-blue-600 via-sky-500 to-cyan-400 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-72 h-72 bg-white/10 rounded-full -translate-x-20 -translate-y-20"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-white/10 rounded-full translate-x-20 translate-y-20"></div>

        <div class="max-w-7xl mx-auto px-6 py-8">
            <nav class="flex items-center justify-between">
                <h1 class="text-3xl font-black text-white tracking-wider">SIPKOS</h1>
                <div class="flex gap-3">
                    <a href="{{ route('login.user') }}"
                        class="px-5 py-2.5 rounded-xl bg-white text-blue-600 font-bold shadow hover:bg-slate-100 transition">
                        Login Pengguna
                    </a>
                    <a href="{{ route('login.admin') }}"
                        class="px-5 py-2.5 rounded-xl border border-white text-white font-bold hover:bg-white/10 transition">
                        Login Admin
                    </a>
                </div>
            </nav>

            <div class="grid lg:grid-cols-2 gap-12 items-center pt-16">
                <div class="text-white">
                    <span class="inline-block bg-white/15 px-4 py-2 rounded-full text-sm font-bold mb-6">
                        SIPKOS - Sistem Informasi Pencarian Kos Modern
                    </span>

                    <h2 class="text-5xl lg:text-6xl font-black leading-tight mb-6">
                        Temukan Kos Nyaman, Strategis, dan Sesuai Budgetmu
                    </h2>

                    <p class="text-lg text-white/90 leading-relaxed mb-8">
                        SIPKOS membantu pengguna mencari kos berdasarkan lokasi, harga, tipe kos, fasilitas,
                        maps terdekat, booking online, pembayaran, hingga jasa pindahan.
                    </p>

                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('register') }}"
                            class="px-7 py-4 rounded-2xl bg-white text-blue-600 font-extrabold shadow-lg hover:scale-[1.02] transition">
                            Daftar Sekarang
                        </a>
                        <a href="{{ route('login.user') }}"
                            class="px-7 py-4 rounded-2xl border border-white text-white font-extrabold hover:bg-white/10 transition">
                            Mulai Cari Kos
                        </a>
                    </div>

                    <div class="grid grid-cols-3 gap-4 mt-12">
                        <div class="bg-white/15 backdrop-blur-sm rounded-2xl p-5">
                            <h3 class="text-3xl font-black">20+</h3>
                            <p class="text-sm text-white/90">Kos Aktif</p>
                        </div>
                        <div class="bg-white/15 backdrop-blur-sm rounded-2xl p-5">
                            <h3 class="text-3xl font-black">3</h3>
                            <p class="text-sm text-white/90">Tipe Kos</p>
                        </div>
                        <div class="bg-white/15 backdrop-blur-sm rounded-2xl p-5">
                            <h3 class="text-3xl font-black">AI</h3>
                            <p class="text-sm text-white/90">Chatbot Pintar</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white/15 backdrop-blur-md rounded-[2rem] p-6 shadow-2xl border border-white/20">
                    <div class="bg-white rounded-[1.5rem] p-6 shadow-xl">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="rounded-2xl bg-blue-50 p-5">
                                <p class="text-xs font-black text-blue-500 uppercase">Pencarian Cepat</p>
                                <h4 class="text-lg font-black mt-2">Cari kos sesuai kebutuhan</h4>
                            </div>
                            <div class="rounded-2xl bg-sky-50 p-5">
                                <p class="text-xs font-black text-sky-500 uppercase">Maps</p>
                                <h4 class="text-lg font-black mt-2">Lihat kos terdekat</h4>
                            </div>
                            <div class="rounded-2xl bg-indigo-50 p-5">
                                <p class="text-xs font-black text-indigo-500 uppercase">Booking</p>
                                <h4 class="text-lg font-black mt-2">Booking langsung</h4>
                            </div>
                            <div class="rounded-2xl bg-cyan-50 p-5">
                                <p class="text-xs font-black text-cyan-500 uppercase">Pembayaran</p>
                                <h4 class="text-lg font-black mt-2">QRIS, DANA, Rekening</h4>
                            </div>
                        </div>

                        <div class="mt-6 rounded-2xl bg-slate-100 p-6">
                            <p class="text-sm font-semibold text-slate-500 mb-2">Kenapa pilih SIPKOS?</p>
                            <ul class="space-y-3 text-sm text-slate-700 font-medium">
                                <li>✅ Tampilan modern dan mudah digunakan</li>
                                <li>✅ Pencarian kos berdasarkan filter</li>
                                <li>✅ Chatbot AI rekomendasi kos</li>
                                <li>✅ Maps lokasi kos secara real</li>
                                <li>✅ Booking dan pembayaran lebih praktis</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</body>
</html>