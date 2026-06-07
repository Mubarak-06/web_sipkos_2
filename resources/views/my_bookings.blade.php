@extends('layouts.app')

@section('content')
    <div class="min-h-[80vh] flex items-center justify-center p-4">
        <div
            class="bg-white w-full max-w-md rounded-[2rem] shadow-2xl border border-slate-100 p-8 space-y-8 animate-fade-in text-left">

            <div class="text-center border-b pb-4">
                <h3 class="text-lg font-black text-slate-800 tracking-wider uppercase">Detail Booking</h3>
                <p class="text-xs text-slate-400 font-medium mt-1">Ringkasan transaksi pemesanan kos Anda</p>
            </div>

            @if(session()->has('last_booking'))
                <div class="space-y-5">
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Nama Kos</label>
                        <p class="text-sm font-bold text-slate-800">{{ session('last_booking.nama_kos') }}</p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Lokasi / Alamat</label>
                        <p class="text-sm font-bold text-slate-800">
                            {{ session('last_booking.alamat') }}
                        </p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Tipe Kost</label>
                        <div>
                            <span
                                class="inline-block px-2.5 py-1 bg-blue-50 text-blue-600 rounded-md text-xs font-black uppercase tracking-wide">
                                Kost {{ session('last_booking.tipe_kamar') ?? 'Campur' }}
                            </span>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Tanggal Check-In</label>
                        <p class="text-sm font-bold text-slate-800">
                            {{ session('last_booking.tanggal_checkin') ? \Carbon\Carbon::parse(session('last_booking.tanggal_checkin'))->translatedFormat('d F Y') : '-' }}
                        </p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Durasi Sewa</label>
                        <p class="text-sm font-bold text-slate-800">
                            {{ session('last_booking.durasi', 1) }} Bulan
                        </p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-wider">
                            Layanan Tambahan
                        </label>

                        @if(session('last_booking.jasa_pindahan'))
                            <p class="text-sm font-bold text-blue-600">
                                {{ session('last_booking.nama_jasa_pindahan') }}
                            </p>
                        @else
                            <p class="text-sm font-bold text-slate-500">
                                Tanpa Jasa Pindahan
                            </p>
                        @endif
                    </div>

                    <div class="space-y-1 pt-3 border-t border-dashed border-slate-200">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Total Harga</label>
                        <p class="text-xl font-black text-blue-600">
                            Rp {{ number_format(session('last_booking.total_harga', 0), 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="pt-4">
                        <a href="{{ route('home') }}"
                            class="block text-center bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-3 rounded-xl transition text-xs tracking-wide">
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
            @else
                <div class="text-center py-8 space-y-4">
                    <div
                        class="w-16 h-16 bg-slate-50 mx-auto rounded-full flex items-center justify-center text-slate-300 border border-slate-100">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.008 1.24l.885 1.77a2.25 2.25 0 0 0 2.007 1.24h1.98a2.25 2.25 0 0 0 2.007-1.24l.885-1.77a2.25 2.25 0 0 1 2.007-1.24h3.86m-18 0h18m-18 0v-7.5A2.25 2.25 0 0 1 5.25 6h13.5A2.25 2.25 0 0 1 21 8.25v7.5m-18 0v5.25c0 .621.504 1.125 1.125 1.125h14.25c.621 0 1.125-.504 1.125-1.125v-5.25" />
                        </svg>
                    </div>
                    <div class="space-y-1">
                        <p class="text-sm font-bold text-slate-700">Belum Ada Transaksi</p>
                        <p class="text-xs text-slate-400 font-medium max-w-xs mx-auto">Anda belum melakukan pemesanan kamar kos
                            apa pun dalam sesi ini.</p>
                    </div>
                    <div class="pt-4">
                        <a href="{{ route('home') }}"
                            class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-bold px-6 py-2.5 rounded-xl transition text-xs shadow-md shadow-blue-100 tracking-wide">
                            Cari Kos Sekarang
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection