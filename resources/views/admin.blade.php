@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">

        @if(session('sukses'))
            <div
                class="bg-blue-50 border border-blue-200 text-blue-600 px-4 py-3 rounded-xl text-sm font-semibold shadow-sm text-left">
                {{ session('sukses') }}
            </div>
        @endif

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm text-left">
            <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-1">+ Tambah Properti Kos</h3>

            <form action="{{ route('kos.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <input type="text" name="nama" placeholder="Nama Kos" required
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-100 outline-none text-sm border border-transparent focus:bg-white focus:border-blue-500 transition">

                    <input type="text" name="lokasi" placeholder="Lokasi" required
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-100 outline-none text-sm border border-transparent focus:bg-white focus:border-blue-500 transition">

                    <input type="text" name="alamat" placeholder="Alamat Lengkap" required
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-100 outline-none text-sm border border-transparent focus:bg-white focus:border-blue-500 transition">

                    <input type="number" name="harga" placeholder="Harga / Bulan (Rp)" required
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-100 outline-none text-sm border border-transparent focus:bg-white focus:border-blue-500 transition">

                    <input type="text" name="no_telepon" placeholder="No WhatsApp (628...)" required
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-100 outline-none text-sm border border-transparent focus:bg-white focus:border-blue-500 transition">

                    <select name="tipe_kos" required
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-100 outline-none text-sm text-slate-600 border border-transparent focus:bg-white focus:border-blue-500 transition">
                        <option value="">-- Tipe Kos --</option>
                        <option value="Pria">Pria</option>
                        <option value="Wanita">Wanita</option>
                        <option value="Campur">Campur</option>
                    </select>
                </div>

                <div class="flex flex-wrap gap-6 py-1 px-2 text-sm font-semibold text-slate-600">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="ac" value="1"
                            class="w-4 h-4 rounded text-blue-500 focus:ring-blue-500"> AC
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="wifi" value="1"
                            class="w-4 h-4 rounded text-blue-500 focus:ring-blue-500"> Wifi / Internet
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="kamar_mandi_dalam" value="1"
                            class="w-4 h-4 rounded text-blue-500 focus:ring-blue-500"> Kamar Mandi Dalam
                    </label>
                </div>

                <input type="text" name="deskripsi" placeholder="Deskripsi singkat mengenai fasilitas properti kos..."
                    required
                    class="w-full px-4 py-2.5 rounded-xl bg-slate-100 outline-none text-sm border border-transparent focus:bg-white focus:border-blue-500 transition">

                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white text-xs font-bold px-6 py-2.5 rounded-xl shadow-md transition">
                    Simpan Properti
                </button>
            </form>
        </div>

        <div class="space-y-3 text-left">
            <h4 class="text-sm font-bold text-slate-800 ml-1">Daftar Kos Aktif</h4>

            @forelse ($semuaKos as $kos)
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between gap-4">
                    <div>
                        <span class="text-sm font-bold text-slate-700 block">{{ $kos->nama }}</span>
                        <span class="text-xs text-slate-400 font-medium">{{ $kos->lokasi }} &bull; Rp
                            {{ number_format($kos->harga, 0, ',', '.') }}/bln</span>
                    </div>
                    <div class="flex gap-2 text-xs font-bold shrink-0">
                        <a href="{{ route('kos.edit', $kos->id) }}"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-1.5 rounded-lg transition inline-block text-center shadow-sm">
                            Edit
                        </a>

                        <form action="{{ route('kos.destroy', $kos->id) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus kos ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-1.5 rounded-lg transition shadow-sm">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div
                    class="bg-white p-8 rounded-xl border border-dashed border-slate-200 text-center text-sm text-slate-400 font-medium">
                    Belum ada data kos di database. Silakan tambah data di atas!
                </div>
            @endforelse
        </div>
    </div>
@endsection