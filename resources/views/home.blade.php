@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

<style>
    #sipkosMap {
        height: 430px;
        width: 100%;
        border-radius: 1.5rem;
        z-index: 1;
    }

    .leaflet-container {
        font-family: inherit;
    }

    .line-clamp-1 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
    }

    .line-clamp-2 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .popup-kos-card {
        width: 235px;
        font-family: inherit;
    }

    .popup-kos-img {
        width: 100%;
        height: 105px;
        object-fit: cover;
        border-radius: 12px;
        margin-bottom: 10px;
        background: #f1f5f9;
    }

    .popup-kos-placeholder {
        width: 100%;
        height: 105px;
        border-radius: 12px;
        background: linear-gradient(135deg, #eff6ff, #f8fafc);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        font-size: 12px;
        font-weight: 800;
        margin-bottom: 10px;
    }

    .popup-kos-title {
        font-size: 14px;
        font-weight: 900;
        color: #0f172a;
        margin-bottom: 4px;
    }

    .popup-kos-info {
        font-size: 11px;
        color: #64748b;
        line-height: 1.5;
        margin-bottom: 8px;
    }

    .popup-kos-price {
        font-size: 13px;
        font-weight: 900;
        color: #2563eb;
        margin-bottom: 10px;
    }

    .popup-kos-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 6px;
    }

    .popup-kos-btn {
        display: block;
        text-align: center;
        text-decoration: none;
        padding: 8px 10px;
        border-radius: 10px;
        font-size: 10px;
        font-weight: 900;
    }

    .popup-kos-btn-detail {
        background: #2563eb;
        color: white !important;
    }

    .popup-kos-btn-booking {
        background: #16a34a;
        color: white !important;
    }

    .popup-kos-btn-route {
        background: #f1f5f9;
        color: #334155 !important;
        margin-top: 7px;
    }

    .leaflet-popup-content {
        margin: 12px;
    }
</style>

@php
    $kosMapData = $semuaKos->map(function ($item) {
        return [
            'id' => $item->id,
            'nama' => $item->nama,
            'lokasi' => $item->lokasi,
            'alamat' => $item->alamat,
            'harga' => $item->harga,
            'tipe_kos' => $item->tipe_kos,
            'lat' => $item->latitude ?? null,
            'lng' => $item->longitude ?? null,
            'detail_url' => route('kos.show', $item->id),
            'booking_url' => route('kos.booking', $item->id),
            'foto' => $item->foto ? asset('foto-kos/' . $item->foto) : null,
        ];
    })->values();
@endphp

<section class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-blue-600 via-blue-500 to-sky-400 text-white p-8 md:p-10 mb-10 shadow-xl">
    <div class="absolute -right-16 -top-16 w-64 h-64 bg-white/10 rounded-full"></div>
    <div class="absolute -left-20 -bottom-20 w-72 h-72 bg-white/10 rounded-full"></div>

    <div class="relative grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
        <div>
            <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-white/20 text-xs font-bold mb-4">
                SIPKOS - Sistem Informasi Kos
            </span>

            <h1 class="text-3xl md:text-5xl font-black leading-tight mb-4">
                Temukan Kos Nyaman, Strategis, dan Sesuai Kebutuhanmu
            </h1>

            <p class="text-blue-50 text-sm md:text-base leading-relaxed max-w-xl">
                Cari kos berdasarkan lokasi, harga, fasilitas, tipe kos, dan lihat kos terdekat dari lokasi Anda melalui maps.
            </p>
        </div>

        <div class="grid grid-cols-3 gap-3">
            <div class="bg-white/20 backdrop-blur p-4 rounded-2xl text-center">
                <p class="text-2xl font-black">{{ $semuaKos->count() }}</p>
                <p class="text-xs font-semibold text-blue-50">Kos Aktif</p>
            </div>

            <div class="bg-white/20 backdrop-blur p-4 rounded-2xl text-center">
                <p class="text-2xl font-black">3</p>
                <p class="text-xs font-semibold text-blue-50">Tipe Kos</p>
            </div>

            <div class="bg-white/20 backdrop-blur p-4 rounded-2xl text-center">
                <p class="text-2xl font-black">Maps</p>
                <p class="text-xs font-semibold text-blue-50">Terdekat</p>
            </div>
        </div>
    </div>
</section>

<div class="max-w-4xl mx-auto -mt-16 relative z-20 mb-10">
    <form action="{{ route('home') }}" method="GET" id="mainSearchForm">
        <div class="flex gap-2 p-2 bg-white rounded-full shadow-xl border border-slate-100 items-center">

            <button type="button" id="btnOpenFilter"
                class="px-5 py-3 text-sm font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-600 rounded-full transition flex items-center gap-2 shrink-0">
                Filter
                @if(request()->anyFilled(['tipe_kos', 'harga_maksimal']) || request()->has('ac') || request()->has('wifi') || request()->has('kamar_mandi_dalam'))
                    <span class="w-2 h-2 bg-blue-500 rounded-full inline-block"></span>
                @endif
            </button>

            <div class="w-px h-7 bg-slate-200"></div>

            <input type="text" name="keyword" value="{{ request('keyword') }}"
                class="w-full px-4 bg-transparent focus:outline-none text-sm text-slate-700"
                placeholder="Cari nama kos atau lokasi...">

            @if(request()->anyFilled(['keyword', 'tipe_kos', 'harga_maksimal']) || request()->has('ac') || request()->has('wifi') || request()->has('kamar_mandi_dalam'))
                <a href="{{ route('home') }}" class="text-slate-400 hover:text-slate-600 text-lg px-2"
                    title="Reset Semua Filter">&times;</a>
            @endif

            <button type="submit"
                class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-bold px-7 py-3 rounded-full transition shadow-md shrink-0">
                Cari
            </button>
        </div>

        <div id="filterModal"
            class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
            <div class="bg-white w-full max-w-md rounded-[2rem] shadow-2xl border border-slate-100 flex flex-col max-h-[85vh] overflow-hidden">

                <div class="p-6 border-b border-slate-100 flex justify-between items-center shrink-0">
                    <h3 class="text-base font-black text-slate-800 tracking-wide">Filter Pencarian</h3>
                    <button type="button" id="btnCloseFilter"
                        class="text-slate-400 hover:text-slate-600 text-2xl font-semibold transition">&times;</button>
                </div>

                <div class="p-6 space-y-6 overflow-y-auto flex-grow text-left">
                    <div class="space-y-3">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-wider">
                            Tipe Kos
                        </label>

                        <div class="grid grid-cols-3 gap-2">
                            <label class="cursor-pointer text-center">
                                <input type="radio" name="tipe_kos" value="Pria"
                                    {{ request('tipe_kos') == 'Pria' ? 'checked' : '' }} class="peer hidden">
                                <div
                                    class="py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-600 hover:bg-slate-50 transition">
                                    Pria
                                </div>
                            </label>

                            <label class="cursor-pointer text-center">
                                <input type="radio" name="tipe_kos" value="Wanita"
                                    {{ request('tipe_kos') == 'Wanita' ? 'checked' : '' }} class="peer hidden">
                                <div
                                    class="py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 peer-checked:border-pink-500 peer-checked:bg-pink-50 peer-checked:text-pink-600 hover:bg-slate-50 transition">
                                    Wanita
                                </div>
                            </label>

                            <label class="cursor-pointer text-center">
                                <input type="radio" name="tipe_kos" value="Campur"
                                    {{ request('tipe_kos') == 'Campur' ? 'checked' : '' }} class="peer hidden">
                                <div
                                    class="py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 peer-checked:border-purple-500 peer-checked:bg-purple-50 peer-checked:text-purple-600 hover:bg-slate-50 transition">
                                    Campur
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-wider">
                            Batas Harga Maksimal
                        </label>

                        <select name="harga_maksimal"
                            class="w-full h-11 px-3 bg-slate-50 border border-slate-200 rounded-xl outline-none text-xs text-slate-600 focus:border-blue-500 focus:bg-white transition">
                            <option value="">Semua Harga</option>
                            <option value="500000" {{ request('harga_maksimal') == '500000' ? 'selected' : '' }}>
                                Di bawah Rp 500 Ribu
                            </option>
                            <option value="1000000" {{ request('harga_maksimal') == '1000000' ? 'selected' : '' }}>
                                Di bawah Rp 1 Juta
                            </option>
                            <option value="1500000" {{ request('harga_maksimal') == '1500000' ? 'selected' : '' }}>
                                Di bawah Rp 1.5 Juta
                            </option>
                            <option value="2000000" {{ request('harga_maksimal') == '2000000' ? 'selected' : '' }}>
                                Di bawah Rp 2 Juta
                            </option>
                        </select>
                    </div>

                    <div class="space-y-3">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-wider">
                            Fasilitas Properti
                        </label>

                        <div class="grid grid-cols-1 gap-2">
                            <label
                                class="flex items-center justify-between p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 text-xs font-bold text-slate-700">
                                <span>❄️ AC</span>
                                <input type="checkbox" name="ac" {{ request()->has('ac') ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-500 rounded border-slate-300 focus:ring-blue-500">
                            </label>

                            <label
                                class="flex items-center justify-between p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 text-xs font-bold text-slate-700">
                                <span>🌐 Wifi / Internet</span>
                                <input type="checkbox" name="wifi" {{ request()->has('wifi') ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-500 rounded border-slate-300 focus:ring-blue-500">
                            </label>

                            <label
                                class="flex items-center justify-between p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 text-xs font-bold text-slate-700">
                                <span>🚿 Kamar Mandi Dalam</span>
                                <input type="checkbox" name="kamar_mandi_dalam"
                                    {{ request()->has('kamar_mandi_dalam') ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-500 rounded border-slate-300 focus:ring-blue-500">
                            </label>
                        </div>
                    </div>
                </div>

                <div class="p-4 border-t border-slate-100 bg-slate-50 flex gap-3 shrink-0">
                    <button type="button" id="btnResetModal"
                        class="flex-1 py-3 text-xs font-bold text-slate-500 hover:text-slate-700 transition">
                        Reset Filter
                    </button>

                    <button type="submit"
                        class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-xs font-bold py-3 rounded-xl shadow-md transition">
                        Tampilkan Hasil
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<section class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
    <div class="lg:col-span-2 bg-white p-4 rounded-[2rem] border border-slate-200 shadow-sm">
        <div id="sipkosMap"></div>
    </div>

    <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm space-y-5">
        <div>
            <span class="inline-flex px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-[11px] font-black uppercase">
                Maps Terdekat
            </span>

            <h2 class="text-2xl font-black text-slate-800 mt-3">
                Cari Kos Terdekat dari Lokasimu
            </h2>

            <p class="text-sm text-slate-500 leading-relaxed mt-2">
                Tekan tombol di bawah, izinkan akses lokasi, lalu sistem akan mengurutkan kos dari jarak terdekat.
            </p>
        </div>

        <button type="button" id="btnLokasiSaya"
            class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 rounded-xl shadow-md transition text-sm">
            Gunakan Lokasi Saya
        </button>

        <div id="locationStatus"
            class="bg-slate-50 border border-slate-100 rounded-2xl p-4 text-xs text-slate-500 font-semibold leading-relaxed">
            Marker kos dapat diklik untuk melihat detail, booking, dan rute. Aktifkan lokasi untuk mengurutkan berdasarkan jarak terdekat.
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                <p class="text-xl font-black text-slate-800">{{ $semuaKos->count() }}</p>
                <p class="text-xs text-slate-400 font-bold">Total Kos</p>
            </div>

            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                <p class="text-xl font-black text-slate-800" id="jumlahKosMaps">0</p>
                <p class="text-xs text-slate-400 font-bold">Titik Maps</p>
            </div>
        </div>
    </div>
</section>

<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
    <div>
        <h2 class="text-2xl font-black text-slate-800">
            Temukan Kos Impian Anda
        </h2>

        <p class="text-sm text-slate-400 font-medium mt-1">
            Pilih kos sesuai lokasi, harga, fasilitas, dan jarak terdekat.
        </p>
    </div>

    <div class="text-xs font-bold text-slate-500 bg-white px-4 py-2 rounded-full border border-slate-200 shadow-sm">
        Menampilkan {{ $semuaKos->count() }} data kos
    </div>
</div>

<div id="kosGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse ($semuaKos as $kos)
        <div
            class="kos-card group bg-white rounded-[1.7rem] overflow-hidden border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition duration-300 flex flex-col"
            data-id="{{ $kos->id }}"
            data-lat="{{ $kos->latitude ?? '' }}"
            data-lng="{{ $kos->longitude ?? '' }}">

            <div class="h-52 bg-slate-100 overflow-hidden relative shrink-0">
                @if($kos->foto)
                    <img src="{{ asset('foto-kos/' . $kos->foto) }}" alt="{{ $kos->nama }}"
                        class="w-full h-full object-cover object-center group-hover:scale-105 transition duration-500">
                @else
                    <div
                        class="w-full h-full flex flex-col items-center justify-center gap-2 bg-gradient-to-br from-blue-50 to-slate-100 text-slate-400">
                        <span class="text-3xl">🏠</span>
                        <span class="text-xs font-bold">Belum ada foto</span>
                    </div>
                @endif

                <div class="absolute top-4 left-4">
                    <span
                        class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider shadow-sm
                        {{ $kos->tipe_kos == 'Pria' ? 'bg-blue-50 text-blue-600' : ($kos->tipe_kos == 'Wanita' ? 'bg-pink-50 text-pink-600' : 'bg-purple-50 text-purple-600') }}">
                        {{ $kos->tipe_kos }}
                    </span>
                </div>

                <div
                    class="distance-badge hidden absolute top-4 right-4 bg-white/95 text-blue-600 px-3 py-1 rounded-full text-[10px] font-black shadow-sm">
                    <span class="distance-label">-</span>
                </div>
            </div>

            <div class="p-5 flex-grow space-y-4 text-left">
                <div>
                    <div class="flex items-center justify-between gap-3 mb-2">
                        <h3 class="font-black text-base text-slate-800 line-clamp-1">
                            {{ $kos->nama }}
                        </h3>

                        <span class="text-xs font-bold text-slate-400 shrink-0">
                            {{ $kos->lokasi }}
                        </span>
                    </div>

                    <p class="text-xs text-slate-500 font-medium leading-relaxed line-clamp-2 min-h-8">
                        {{ $kos->alamat }}
                    </p>
                </div>

                <div>
                    <p class="text-lg font-black text-blue-600">
                        Rp {{ number_format($kos->harga, 0, ',', '.') }}
                        <span class="text-[11px] text-slate-400 font-semibold">/ bulan</span>
                    </p>

                    <div class="flex flex-wrap gap-2 text-[10px] font-bold text-slate-500 mt-3">
                        @if($kos->ac)
                            <span class="px-2.5 py-1 bg-slate-50 rounded-full border border-slate-100">❄️ AC</span>
                        @endif

                        @if($kos->wifi)
                            <span class="px-2.5 py-1 bg-slate-50 rounded-full border border-slate-100">🌐 Wifi</span>
                        @endif

                        @if($kos->kamar_mandi_dalam)
                            <span class="px-2.5 py-1 bg-slate-50 rounded-full border border-slate-100">🚿 K.Mandi</span>
                        @endif

                        @if(!$kos->ac && !$kos->wifi && !$kos->kamar_mandi_dalam)
                            <span class="px-2.5 py-1 bg-slate-50 rounded-full border border-slate-100">
                                Fasilitas standar
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="p-5 bg-slate-50 shrink-0">
                <a href="{{ route('kos.show', $kos->id) }}"
                    class="block text-center bg-blue-500 hover:bg-blue-600 text-white font-black py-3 rounded-xl transition text-xs tracking-wider uppercase shadow-md shadow-blue-100">
                    Lihat Detail Kos
                </a>
            </div>
        </div>
    @empty
        <div
            class="col-span-1 md:col-span-2 lg:col-span-3 bg-white p-12 rounded-2xl border border-dashed border-slate-200 text-center text-sm text-slate-400 font-medium">
            Tidak ada kos yang cocok dengan kriteria pencarian Anda.
        </div>
    @endforelse
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    const btnOpenFilter = document.getElementById('btnOpenFilter');
    const btnCloseFilter = document.getElementById('btnCloseFilter');
    const filterModal = document.getElementById('filterModal');
    const btnResetModal = document.getElementById('btnResetModal');

    btnOpenFilter.addEventListener('click', () => {
        filterModal.classList.remove('hidden');
    });

    btnCloseFilter.addEventListener('click', () => {
        filterModal.classList.add('hidden');
    });

    filterModal.addEventListener('click', (e) => {
        if (e.target === filterModal) {
            filterModal.classList.add('hidden');
        }
    });

    btnResetModal.addEventListener('click', () => {
        document.querySelectorAll('input[name="tipe_kos"]').forEach(radio => radio.checked = false);

        const hargaSelect = document.querySelector('select[name="harga_maksimal"]');
        if (hargaSelect) {
            hargaSelect.value = "";
        }

        document.querySelectorAll('#filterModal input[type="checkbox"]').forEach(cb => cb.checked = false);
    });
</script>

<script>
    const kosData = @json($kosMapData);

    const defaultCenter = [-3.3194, 114.5908];

    const map = L.map('sipkosMap').setView(defaultCenter, 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    const kosMarkers = {};
    const markerList = [];

    const kosIcon = L.divIcon({
        className: '',
        html: `
            <div style="
                width: 40px;
                height: 40px;
                background: #2563eb;
                border: 3px solid white;
                border-radius: 50% 50% 50% 0;
                transform: rotate(-45deg);
                box-shadow: 0 8px 20px rgba(37,99,235,.45);
                display: flex;
                align-items: center;
                justify-content: center;
            ">
                <span style="
                    transform: rotate(45deg);
                    color: white;
                    font-size: 18px;
                ">🏠</span>
            </div>
        `,
        iconSize: [40, 40],
        iconAnchor: [20, 40],
        popupAnchor: [0, -36],
    });

    const userIcon = L.divIcon({
        className: '',
        html: `
            <div style="
                width: 36px;
                height: 36px;
                background: #0ea5e9;
                border: 3px solid white;
                border-radius: 50%;
                box-shadow: 0 8px 20px rgba(14,165,233,.35);
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 16px;
            ">📍</div>
        `,
        iconSize: [36, 36],
        iconAnchor: [18, 18],
        popupAnchor: [0, -18],
    });

    function escapeHtml(text) {
        if (!text) return '';

        return text.toString()
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function formatRupiah(number) {
        return Number(number).toLocaleString('id-ID');
    }

    function isValidCoordinate(lat, lng) {
        return lat !== null &&
            lng !== null &&
            lat !== "" &&
            lng !== "" &&
            !isNaN(parseFloat(lat)) &&
            !isNaN(parseFloat(lng));
    }

    function getKosCoordinate(kos, index) {
        if (isValidCoordinate(kos.lat, kos.lng)) {
            return {
                lat: parseFloat(kos.lat),
                lng: parseFloat(kos.lng),
                isFallback: false
            };
        }

        const radius = 0.018;
        const angle = index * 0.65;

        return {
            lat: defaultCenter[0] + Math.sin(angle) * radius,
            lng: defaultCenter[1] + Math.cos(angle) * radius,
            isFallback: true
        };
    }

    function buatPopupKos(kos, lat, lng, jarak = null, isFallback = false) {
        const fotoHtml = kos.foto
            ? `<img src="${kos.foto}" class="popup-kos-img" alt="${escapeHtml(kos.nama)}">`
            : `<div class="popup-kos-placeholder">🏠 Belum ada foto</div>`;

        const jarakHtml = jarak !== null
            ? `<br><strong>Jarak:</strong> ${jarak.toFixed(1)} km dari lokasi Anda`
            : '';

        const fallbackInfo = isFallback
            ? `<br><span style="color:#f97316;font-weight:800;">Titik maps sementara</span>`
            : '';

        const routeUrl = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;

        return `
            <div class="popup-kos-card">
                ${fotoHtml}

                <div class="popup-kos-title">
                    ${escapeHtml(kos.nama)}
                </div>

                <div class="popup-kos-info">
                    <strong>${escapeHtml(kos.tipe_kos)}</strong> • ${escapeHtml(kos.lokasi)}<br>
                    ${escapeHtml(kos.alamat)}
                    ${jarakHtml}
                    ${fallbackInfo}
                </div>

                <div class="popup-kos-price">
                    Rp ${formatRupiah(kos.harga)} / bulan
                </div>

                <div class="popup-kos-actions">
                    <a href="${kos.detail_url}" class="popup-kos-btn popup-kos-btn-detail">
                        Detail
                    </a>

                    <a href="${kos.booking_url}" class="popup-kos-btn popup-kos-btn-booking">
                        Booking
                    </a>
                </div>

                <a href="${routeUrl}" target="_blank" class="popup-kos-btn popup-kos-btn-route">
                    Lihat Rute di Google Maps
                </a>
            </div>
        `;
    }

    kosData.forEach((kos, index) => {
        const koordinat = getKosCoordinate(kos, index);
        const lat = koordinat.lat;
        const lng = koordinat.lng;

        const marker = L.marker([lat, lng], {
            icon: kosIcon
        }).addTo(map);

        marker.bindPopup(buatPopupKos(kos, lat, lng, null, koordinat.isFallback), {
            maxWidth: 285
        });

        marker.on('click', function () {
            marker.openPopup();
        });

        kosMarkers[kos.id] = {
            marker: marker,
            lat: lat,
            lng: lng,
            isFallback: koordinat.isFallback
        };

        markerList.push(marker);
    });

    const jumlahKosMaps = document.getElementById('jumlahKosMaps');
    if (jumlahKosMaps) {
        jumlahKosMaps.innerText = markerList.length;
    }

    if (markerList.length > 0) {
        const group = L.featureGroup(markerList);
        map.fitBounds(group.getBounds().pad(0.18));
    }

    let userMarker = null;
    let userCircle = null;

    function hitungJarak(lat1, lon1, lat2, lon2) {
        const R = 6371;

        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;

        const a =
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(lat1 * Math.PI / 180) *
            Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLon / 2) *
            Math.sin(dLon / 2);

        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

        return R * c;
    }

    function urutkanKosTerdekat(userLat, userLng) {
        const grid = document.getElementById('kosGrid');
        const cards = Array.from(document.querySelectorAll('.kos-card'));

        cards.forEach(card => {
            const dataMarker = kosMarkers[card.dataset.id];

            if (dataMarker) {
                const lat = dataMarker.lat;
                const lng = dataMarker.lng;

                const jarak = hitungJarak(userLat, userLng, lat, lng);
                card.dataset.distance = jarak;

                const label = card.querySelector('.distance-label');
                const badge = card.querySelector('.distance-badge');

                if (label && badge) {
                    label.innerText = jarak.toFixed(1) + ' km';
                    badge.classList.remove('hidden');
                }

                const kos = kosData.find(item => item.id == card.dataset.id);

                dataMarker.marker.bindPopup(
                    buatPopupKos(kos, lat, lng, jarak, dataMarker.isFallback),
                    {
                        maxWidth: 285
                    }
                );

            } else {
                card.dataset.distance = 999999;
            }
        });

        cards
            .sort((a, b) => parseFloat(a.dataset.distance) - parseFloat(b.dataset.distance))
            .forEach(card => grid.appendChild(card));
    }

    document.querySelectorAll('.kos-card').forEach(card => {
        card.addEventListener('mouseenter', function () {
            const dataMarker = kosMarkers[this.dataset.id];

            if (dataMarker) {
                dataMarker.marker.openPopup();
                map.panTo(dataMarker.marker.getLatLng());
            }
        });
    });

    document.getElementById('btnLokasiSaya').addEventListener('click', function () {
        const statusBox = document.getElementById('locationStatus');

        if (!navigator.geolocation) {
            statusBox.innerText = 'Browser Anda belum mendukung fitur geolocation.';
            return;
        }

        statusBox.innerText = 'Sedang mengambil lokasi Anda...';

        navigator.geolocation.getCurrentPosition(
            function (position) {
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;

                if (userMarker) {
                    map.removeLayer(userMarker);
                }

                if (userCircle) {
                    map.removeLayer(userCircle);
                }

                userMarker = L.marker([userLat, userLng], {
                    icon: userIcon
                }).addTo(map)
                    .bindPopup('<strong>Lokasi Anda</strong>')
                    .openPopup();

                userCircle = L.circle([userLat, userLng], {
                    radius: 500,
                    color: '#2563eb',
                    fillColor: '#3b82f6',
                    fillOpacity: 0.12
                }).addTo(map);

                map.setView([userLat, userLng], 14);

                urutkanKosTerdekat(userLat, userLng);

                statusBox.innerText = 'Lokasi berhasil ditemukan. Daftar kos sudah diurutkan dari jarak terdekat. Klik marker kos untuk detail, booking, dan rute.';
            },
            function () {
                statusBox.innerText = 'Gagal mengambil lokasi. Pastikan Anda mengizinkan akses lokasi di browser.';
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    });
</script>

@endsection