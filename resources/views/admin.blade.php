@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

    <style>
        #adminMap {
            width: 100%;
            height: 340px;
            border-radius: 1.25rem;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            z-index: 1;
        }

        .leaflet-container {
            font-family: inherit;
        }

        .dataTables_wrapper .dataTables_filter input,
        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 0.4rem 0.7rem;
            outline: none;
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #3b82f6;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #3b82f6 !important;
            color: white !important;
            border: none !important;
            border-radius: 0.6rem !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 0.6rem !important;
        }
    </style>

    <div class="max-w-6xl mx-auto space-y-8">

        @if(session('sukses'))
            <div
                class="bg-blue-50 border border-blue-200 text-blue-600 px-4 py-3 rounded-xl text-sm font-semibold shadow-sm text-left">
                {{ session('sukses') }}
            </div>
        @endif

        @if($errors->any())
            <div
                class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm font-semibold shadow-sm text-left">
                <p class="font-black mb-2">Data belum lengkap:</p>
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm text-left">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-5">
                <div>
                    <h3 class="text-lg font-black text-slate-800">
                        + Tambah Properti Kos
                    </h3>

                    <p class="text-xs text-slate-400 font-medium mt-1">
                        Isi data kos dan tentukan titik lokasi di maps agar muncul di halaman Home.
                    </p>
                </div>

                <span
                    class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-[11px] font-black uppercase">
                    Admin Panel
                </span>
            </div>

            <form action="{{ route('kos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Nama Kos" required
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-100 outline-none text-sm border border-transparent focus:bg-white focus:border-blue-500 transition">

                    <input type="text" name="lokasi" value="{{ old('lokasi') }}" placeholder="Lokasi / Area" required
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-100 outline-none text-sm border border-transparent focus:bg-white focus:border-blue-500 transition">

                    <input type="number" name="harga" value="{{ old('harga') }}" placeholder="Harga / Bulan (Rp)" required
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-100 outline-none text-sm border border-transparent focus:bg-white focus:border-blue-500 transition">

                    <input type="text" name="no_telepon" value="{{ old('no_telepon') }}"
                        placeholder="No WhatsApp Pemilik (628...)" required
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-100 outline-none text-sm border border-transparent focus:bg-white focus:border-blue-500 transition">

                    <select name="tipe_kos" required
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-100 outline-none text-sm text-slate-600 border border-transparent focus:bg-white focus:border-blue-500 transition">
                        <option value="">-- Tipe Kos --</option>
                        <option value="Pria" {{ old('tipe_kos') == 'Pria' ? 'selected' : '' }}>Pria</option>
                        <option value="Wanita" {{ old('tipe_kos') == 'Wanita' ? 'selected' : '' }}>Wanita</option>
                        <option value="Campur" {{ old('tipe_kos') == 'Campur' ? 'selected' : '' }}>Campur</option>
                    </select>

                    <input type="text" name="alamat" value="{{ old('alamat') }}" placeholder="Alamat Lengkap" required
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-100 outline-none text-sm border border-transparent focus:bg-white focus:border-blue-500 transition">
                </div>

                <div class="flex flex-wrap gap-6 py-1 px-2 text-sm font-semibold text-slate-600">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="ac" value="1" {{ old('ac') ? 'checked' : '' }}
                            class="w-4 h-4 rounded text-blue-500 focus:ring-blue-500">
                        AC
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="wifi" value="1" {{ old('wifi') ? 'checked' : '' }}
                            class="w-4 h-4 rounded text-blue-500 focus:ring-blue-500">
                        Wifi / Internet
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="kamar_mandi_dalam" value="1"
                            {{ old('kamar_mandi_dalam') ? 'checked' : '' }}
                            class="w-4 h-4 rounded text-blue-500 focus:ring-blue-500">
                        Kamar Mandi Dalam
                    </label>
                </div>

                <textarea name="deskripsi" rows="3" placeholder="Deskripsi singkat mengenai fasilitas properti kos..."
                    required
                    class="w-full px-4 py-3 rounded-xl bg-slate-100 outline-none text-sm border border-transparent focus:bg-white focus:border-blue-500 transition">{{ old('deskripsi') }}</textarea>

                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 space-y-4">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <div>
                            <h4 class="text-sm font-black text-slate-800">
                                Tentukan Titik Lokasi Kos di Maps
                            </h4>

                            <p class="text-xs text-slate-400 font-medium mt-1">
                                Klik titik lokasi kos pada peta. Latitude dan longitude akan terisi otomatis.
                            </p>
                        </div>

                        <button type="button" id="btnLokasiAdmin"
                            class="bg-blue-500 hover:bg-blue-600 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition">
                            Gunakan Lokasi Saya
                        </button>
                    </div>

                    <div id="adminMap"></div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input type="number" step="any" name="latitude" id="inputLatitude"
                            value="{{ old('latitude') }}" placeholder="Latitude" readonly required
                            class="w-full px-4 py-2.5 rounded-xl bg-white outline-none text-sm border border-slate-200 focus:border-blue-500 transition">

                        <input type="number" step="any" name="longitude" id="inputLongitude"
                            value="{{ old('longitude') }}" placeholder="Longitude" readonly required
                            class="w-full px-4 py-2.5 rounded-xl bg-white outline-none text-sm border border-slate-200 focus:border-blue-500 transition">
                    </div>

                    <p id="mapStatus" class="text-xs font-semibold text-slate-500">
                        Belum ada titik dipilih. Klik pada maps sebelum menyimpan properti.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 block">Foto Utama Kos (Wajib)</label>
                        <input type="file" name="foto_utama" accept="image/*" required
                            class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100">
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 block">Foto Pendukung 1 (Opsional)</label>
                        <input type="file" name="foto_2" accept="image/*"
                            class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100">
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 block">Foto Pendukung 2 (Opsional)</label>
                        <input type="file" name="foto_3" accept="image/*"
                            class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100">
                    </div>
                </div>

                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white text-xs font-bold px-6 py-3 rounded-xl shadow-md transition">
                    Simpan Properti
                </button>
            </form>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <h4 class="text-lg font-bold text-slate-800 mb-4">
                Daftar Kos Aktif
            </h4>

            <div class="overflow-x-auto">
                <table id="tabelKos" class="display responsive nowrap w-full">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nama Kos</th>
                            <th>Lokasi</th>
                            <th>Tipe</th>
                            <th>Harga</th>
                            <th>Fasilitas</th>
                            <th>Titik Maps</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($semuaKos as $kos)
                            <tr>
                                <td>
                                    @if($kos->foto)
                                        <img src="{{ asset('foto-kos/' . $kos->foto) }}" width="60" class="rounded-lg">
                                    @else
                                        <span class="text-xs text-slate-400 font-bold">No Pic</span>
                                    @endif
                                </td>

                                <td class="font-semibold text-slate-700">
                                    {{ $kos->nama }}
                                </td>

                                <td>{{ $kos->lokasi }}</td>

                                <td>{{ $kos->tipe_kos }}</td>

                                <td>
                                    Rp {{ number_format($kos->harga, 0, ',', '.') }}
                                </td>

                                <td>
                                    @if($kos->ac)
                                        AC<br>
                                    @endif

                                    @if($kos->wifi)
                                        Wifi<br>
                                    @endif

                                    @if($kos->kamar_mandi_dalam)
                                        KM Dalam
                                    @endif

                                    @if(!$kos->ac && !$kos->wifi && !$kos->kamar_mandi_dalam)
                                        -
                                    @endif
                                </td>

                                <td>
                                    @if($kos->latitude && $kos->longitude)
                                        <a href="https://www.google.com/maps?q={{ $kos->latitude }},{{ $kos->longitude }}"
                                            target="_blank"
                                            class="inline-block bg-green-50 text-green-600 px-3 py-1 rounded-lg text-xs font-bold">
                                            Ada
                                        </a>
                                    @else
                                        <span
                                            class="inline-block bg-red-50 text-red-600 px-3 py-1 rounded-lg text-xs font-bold">
                                            Belum
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <div class="flex gap-2">
                                        <a href="{{ route('kos.edit', $kos->id) }}"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">
                                            Edit
                                        </a>

                                        <form action="{{ route('kos.destroy', $kos->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin hapus data kos ini?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <h4 class="text-lg font-bold text-slate-800 mb-4">
                Data Booking Masuk
            </h4>

            <div class="overflow-x-auto">
                <table id="tabelBooking" class="display responsive nowrap w-full">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Kos</th>
                            <th>Check In</th>
                            <th>Durasi</th>
                            <th>Jasa Pindahan</th>
                            <th>Metode Bayar</th>
                            <th>Bukti</th>
                            <th>Total</th>
                            <th>Status Bayar</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($semuaBooking as $booking)
                            <tr>
                                <td>
                                    <span class="font-bold text-slate-700">
                                        {{ $booking->kode_booking ?? '-' }}
                                    </span>
                                </td>

                                <td>
                                    {{ $booking->kos->nama ?? 'Kos sudah dihapus' }}
                                </td>

                                <td>
                                    {{ $booking->tanggal_checkin }}
                                </td>

                                <td>
                                    {{ $booking->durasi }} Bulan
                                </td>

                                <td>
                                    @if($booking->jasa_pindahan)
                                        {{ $booking->nama_jasa_pindahan ?: 'Jasa Pindahan' }}
                                    @else
                                        Tidak
                                    @endif
                                </td>

                                <td>
                                    @if($booking->metode_pembayaran == 'qris')
                                        <span class="px-2 py-1 bg-blue-50 text-blue-600 rounded text-xs font-bold">
                                            QRIS
                                        </span>
                                    @elseif($booking->metode_pembayaran == 'dana')
                                        <span class="px-2 py-1 bg-sky-50 text-sky-600 rounded text-xs font-bold">
                                            DANA
                                        </span>
                                    @elseif($booking->metode_pembayaran == 'transfer_bank')
                                        <span class="px-2 py-1 bg-purple-50 text-purple-600 rounded text-xs font-bold">
                                            Transfer Bank
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    @if($booking->bukti_pembayaran)
                                        <a href="{{ asset('bukti-pembayaran/' . $booking->bukti_pembayaran) }}"
                                            target="_blank"
                                            class="bg-green-50 text-green-600 px-3 py-1 rounded text-xs font-bold">
                                            Lihat Bukti
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400 font-bold">
                                            Belum Ada
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                                </td>

                                <td>
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-bold">
                                        {{ $booking->status_pembayaran ?? 'Menunggu Verifikasi Admin' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-bold">
                                        {{ $booking->status ?? 'pending' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>

                @if($semuaBooking->isEmpty())
                    <div class="p-4 text-center text-slate-400 text-sm font-medium">
                        Belum ada booking.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        $(document).ready(function () {
            $('#tabelKos').DataTable({
                responsive: true,
                pageLength: 5,
                language: {
                    search: "Cari Kos:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    zeroRecords: "Data tidak ditemukan",
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Berikutnya"
                    }
                }
            });

            $('#tabelBooking').DataTable({
                responsive: true,
                pageLength: 5,
                language: {
                    search: "Cari Booking:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    zeroRecords: "Data booking belum ada",
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Berikutnya"
                    }
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const inputLatitude = document.getElementById('inputLatitude');
            const inputLongitude = document.getElementById('inputLongitude');
            const btnLokasiAdmin = document.getElementById('btnLokasiAdmin');
            const mapStatus = document.getElementById('mapStatus');

            const defaultCenter = [-3.3194, 114.5908];

            const adminMap = L.map('adminMap').setView(defaultCenter, 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(adminMap);

            let markerKos = null;

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

            function setKoordinat(lat, lng) {
                inputLatitude.value = lat.toFixed(7);
                inputLongitude.value = lng.toFixed(7);

                if (markerKos) {
                    adminMap.removeLayer(markerKos);
                }

                markerKos = L.marker([lat, lng], {
                    icon: kosIcon
                }).addTo(adminMap)
                    .bindPopup('<strong>Lokasi Kos Dipilih</strong>')
                    .openPopup();

                adminMap.setView([lat, lng], 16);

                mapStatus.innerText = 'Titik lokasi kos sudah dipilih dan siap disimpan.';
                mapStatus.classList.remove('text-slate-500');
                mapStatus.classList.add('text-blue-600');
            }

            adminMap.on('click', function (e) {
                setKoordinat(e.latlng.lat, e.latlng.lng);
            });

            btnLokasiAdmin.addEventListener('click', function () {
                if (!navigator.geolocation) {
                    alert('Browser Anda tidak mendukung fitur lokasi.');
                    return;
                }

                mapStatus.innerText = 'Sedang mengambil lokasi Anda...';

                navigator.geolocation.getCurrentPosition(
                    function (position) {
                        setKoordinat(position.coords.latitude, position.coords.longitude);
                    },
                    function () {
                        alert('Gagal mengambil lokasi. Pastikan izin lokasi di browser aktif.');
                        mapStatus.innerText = 'Gagal mengambil lokasi. Silakan klik lokasi kos secara manual pada maps.';
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            });

            const oldLat = parseFloat(inputLatitude.value);
            const oldLng = parseFloat(inputLongitude.value);

            if (!isNaN(oldLat) && !isNaN(oldLng)) {
                setKoordinat(oldLat, oldLng);
            }

            setTimeout(function () {
                adminMap.invalidateSize();
            }, 500);
        });
    </script>
@endsection