@extends('layouts.app')

@section('content')
<a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-blue-600 mb-6 transition">
    &larr; Kembali ke Pencarian
</a>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-8 text-left">
    <div class="lg:col-span-2 bg-slate-200 rounded-2xl h-80 flex items-center justify-center text-slate-400 font-bold text-lg tracking-wide border border-slate-300 shadow-sm overflow-hidden">
        @if(isset($kos->foto_utama))
            <img src="{{ asset('storage/' . $kos->foto_utama) }}" class="w-full h-full object-cover" alt="Foto Utama">
        @else
            <span>Gambar Fasilitas Utama</span>
        @endif
    </div>
    <div class="grid grid-rows-2 gap-4">
        <div class="bg-slate-200 rounded-2xl flex items-center justify-center text-slate-400 font-bold text-sm tracking-wide border border-slate-300 shadow-sm overflow-hidden">
            <span>Gambar Fasilitas 2</span>
        </div>
        <div class="bg-slate-200 rounded-2xl flex items-center justify-center text-slate-400 font-bold text-sm tracking-wide border border-slate-300 shadow-sm overflow-hidden">
            <span>Gambar Fasilitas 3</span>
        </div>
    </div>
</div>

<div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 text-left">
    <div>
        <h1 class="text-2xl font-black text-slate-800 mb-1">{{ $kos->nama }}</h1>
        <div class="flex items-center gap-1 text-slate-500 text-sm mb-2">
            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            <span class="font-medium">{{ $kos->alamat }}</span>
        </div>
        <p class="text-sm font-medium text-slate-500">{{ $kos->deskripsi }} | <span class="text-blue-600 font-bold">Kost {{ $kos->tipe_kos }}</span></p>
    </div>
    <div class="flex items-center gap-8 w-full md:w-auto justify-between md:justify-end border-t md:border-t-0 pt-4 md:pt-0 border-slate-100">
        <div class="text-right md:text-left">
            <span class="text-slate-400 text-xs font-bold block">Harga Kos</span>
            <span class="text-blue-600 font-black text-xl block tracking-wide">Rp {{ number_format($kos->harga, 0, ',', '.') }}<span class="text-xs text-slate-400 font-normal">/bulan</span></span>
        </div>
        <a href="{{ route('kos.booking', $kos->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold px-8 py-3 rounded-xl transition shadow-md shadow-blue-100 text-sm tracking-wide">
            Booking Sekarang
        </a>
    </div>
</div>

<div class="space-y-4 text-left">
    <h3 class="text-sm font-bold text-slate-800 ml-1">Rekomendasi Kos Sekitar Lainnya</h3>
    
    @forelse($rekomendasi as $rek)
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-24 h-20 bg-slate-200 rounded-lg flex items-center justify-center text-xs text-slate-400 font-bold border border-slate-300">
                    Gambar Kos
                </div>
                <div>
                    <h4 class="font-bold text-sm text-slate-800">{{ $rek->nama }}</h4>
                    <p class="text-xs font-medium text-slate-400 mt-0.5">{{ $rek->lokasi }} - Rp {{ number_format($rek->harga, 0, ',', '.') }}</p>
                </div>
            </div>
            <a href="{{ route('kos.show', $rek->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white text-xs font-bold px-5 py-2 rounded-lg transition shadow-sm">
                Lihat Kos
            </a>
        </div>
    @empty
        <div class="bg-white p-6 rounded-xl border border-dashed border-slate-200 text-center text-xs text-slate-400 font-medium">
            Belum ada rekomendasi kos lain di lokasi sekitar ini.
        </div>
    @endforelse
</div>
@endsection