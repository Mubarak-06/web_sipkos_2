@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6 text-left">
        <a href="{{ route('admin') }}"
            class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-blue-600 mb-2 transition">
            &larr; Kembali ke Dashboard
        </a>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-1">Edit Data Kos: {{ $kos->nama }}</h3>

            <form action="{{ route('kos.update', $kos->id) }}" method="POST" enctype="multipart/form-data"
                class="space-y-4">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <input type="text" name="nama" value="{{ $kos->nama }}" placeholder="Nama Kos" required
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-100 outline-none text-sm border border-transparent focus:bg-white focus:border-blue-500 transition">
                    <input type="text" name="lokasi" value="{{ $kos->lokasi }}" placeholder="Lokasi" required
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-100 outline-none text-sm border border-transparent focus:bg-white focus:border-blue-500 transition">
                    <input type="text" name="alamat" value="{{ $kos->alamat }}" placeholder="Alamat Lengkap" required
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-100 outline-none text-sm border border-transparent focus:bg-white focus:border-blue-500 transition">
                    <input type="number" name="harga" value="{{ $kos->harga }}" placeholder="Harga / Bulan (Rp)" required
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-100 outline-none text-sm border border-transparent focus:bg-white focus:border-blue-500 transition">
                    <input type="text" name="no_telepon" value="{{ $kos->no_telepon }}" placeholder="No Telepon/WA Pemilik"
                        required
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-100 outline-none text-sm border border-transparent focus:bg-white focus:border-blue-500 transition">
                    <select name="tipe_kos" required
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-100 outline-none text-sm text-slate-600 border border-transparent focus:bg-white focus:border-blue-500 transition">
                        <option value="">-- Tipe Kos --</option>
                        <option value="Pria" {{ $kos->tipe_kos == 'Pria' ? 'selected' : '' }}>Pria</option>
                        <option value="Wanita" {{ $kos->tipe_kos == 'Wanita' ? 'selected' : '' }}>Wanita</option>
                        <option value="Campur" {{ $kos->tipe_kos == 'Campur' ? 'selected' : '' }}>Campur</option>
                    </select>
                </div>

                <div class="flex flex-wrap gap-6 py-1 px-2 text-sm font-semibold text-slate-600">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="hidden" name="ac" value="0">
                        <input type="checkbox" name="ac" value="1" {{ $kos->ac ? 'checked' : '' }}
                            class="w-4 h-4 rounded text-blue-500 focus:ring-blue-500"> AC
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="hidden" name="wifi" value="0">
                        <input type="checkbox" name="wifi" value="1" {{ $kos->wifi ? 'checked' : '' }}
                            class="w-4 h-4 rounded text-blue-500 focus:ring-blue-500"> Wifi / Internet
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="hidden" name="kamar_mandi_dalam" value="0">
                        <input type="checkbox" name="kamar_mandi_dalam" value="1" {{ $kos->kamar_mandi_dalam ? 'checked' : '' }} class="w-4 h-4 rounded text-blue-500 focus:ring-blue-500"> Kamar Mandi Dalam
                    </label>
                </div>

                <input type="text" name="deskripsi" value="{{ $kos->deskripsi }}" placeholder="Deskripsi singkat" required
                    class="w-full px-4 py-2.5 rounded-xl bg-slate-100 outline-none text-sm border border-transparent focus:bg-white focus:border-blue-500 transition">

                <div class="space-y-4 pt-2 border-t border-dashed border-slate-100 mt-2">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-wider block">Manajemen Galeri Foto
                        Properti</label>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2 bg-slate-50 p-3 rounded-2xl border border-slate-200/60">
                            <label class="text-xs font-bold text-slate-700 block">Foto Utama</label>
                            <input type="file" name="foto_utama" accept="image/*"
                                class="block w-full text-[11px] text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100">
                            @if($kos->foto)
                                <div class="flex items-center gap-2 pt-1 border-t border-slate-200/60 mt-2">
                                    <div class="w-14 h-11 rounded-lg overflow-hidden border border-slate-200 shrink-0">
                                        <img src="{{ asset('foto-kos/' . $kos->foto) }}" class="w-full h-full object-cover">
                                    </div>
                                    <span class="text-[10px] text-slate-400 font-semibold truncate">Foto Aktif</span>
                                </div>
                            @endif
                        </div>

                        <div class="space-y-2 bg-slate-50 p-3 rounded-2xl border border-slate-200/60">
                            <label class="text-xs font-bold text-slate-700 block">Foto Pendukung 1</label>
                            <input type="file" name="foto_2" accept="image/*"
                                class="block w-full text-[11px] text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100">
                            @if($kos->foto_2)
                                <div class="flex items-center gap-2 pt-1 border-t border-slate-200/60 mt-2">
                                    <div class="w-14 h-11 rounded-lg overflow-hidden border border-slate-200 shrink-0">
                                        <img src="{{ asset('foto-kos/' . $kos->foto_2) }}" class="w-full h-full object-cover">
                                    </div>
                                    <span class="text-[10px] text-slate-400 font-semibold truncate">Foto Aktif</span>
                                </div>
                            @endif
                        </div>

                        <div class="space-y-2 bg-slate-50 p-3 rounded-2xl border border-slate-200/60">
                            <label class="text-xs font-bold text-slate-700 block">Foto Pendukung 2</label>
                            <input type="file" name="foto_3" accept="image/*"
                                class="block w-full text-[11px] text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100">
                            @if($kos->foto_3)
                                <div class="flex items-center gap-2 pt-1 border-t border-slate-200/60 mt-2">
                                    <div class="w-14 h-11 rounded-lg overflow-hidden border border-slate-200 shrink-0">
                                        <img src="{{ asset('foto-kos/' . $kos->foto_3) }}" class="w-full h-full object-cover">
                                    </div>
                                    <span class="text-[10px] text-slate-400 font-semibold truncate">Foto Aktif</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white text-xs font-bold px-6 py-2.5 rounded-xl shadow-md transition">
                    Perbarui Data Kos
                </button>
            </form>
        </div>
    </div>
@endsection