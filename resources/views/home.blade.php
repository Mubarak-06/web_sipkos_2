@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto mb-10">
        <form action="{{ route('home') }}" method="GET" id="mainSearchForm">
            <div class="flex gap-2 p-2 bg-white rounded-full shadow-md border border-slate-100 items-center">

                <button type="button" id="btnOpenFilter"
                    class="px-5 py-2 text-sm font-bold text-slate-600 hover:bg-slate-50 rounded-full transition flex items-center gap-1 shrink-0">
                    Filter
                    @if(request()->anyFilled(['tipe_kos', 'harga_maksimal']) || request()->has('ac') || request()->has('wifi') || request()->has('kamar_mandi_dalam'))
                        <span class="w-2 h-2 bg-blue-500 rounded-full inline-block"></span>
                    @endif
                </button>

                <div class="w-px h-6 bg-slate-200"></div>

                <input type="text" name="keyword" value="{{ request('keyword') }}"
                    class="w-full px-4 bg-transparent focus:outline-none text-sm text-slate-700"
                    placeholder="Cari Nama Kos atau Lokasi...">

                @if(request()->anyFilled(['keyword', 'tipe_kos', 'harga_maksimal']) || request()->has('ac') || request()->has('wifi') || request()->has('kamar_mandi_dalam'))
                    <a href="{{ route('home') }}" class="text-slate-400 hover:text-slate-600 text-lg px-2"
                        title="Reset Semua Filter">&times;</a>
                @endif

                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-bold px-6 py-2.5 rounded-full transition shadow-md shrink-0">
                    Cari
                </button>
            </div>

            <div id="filterModal"
                class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4 transition-all animate-fade-in">
                <div
                    class="bg-white w-full max-w-md rounded-[2rem] shadow-2xl border border-slate-100 flex flex-col max-h-[85vh] overflow-hidden">

                    <div class="p-6 border-b border-slate-100 flex justify-between items-center shrink-0">
                        <h3 class="text-base font-black text-slate-800 tracking-wide">Filter</h3>
                        <button type="button" id="btnCloseFilter"
                            class="text-slate-400 hover:text-slate-600 text-2xl font-semibold transition">&times;</button>
                    </div>

                    <div class="p-6 space-y-6 overflow-y-auto flex-grow text-left">

                        <div class="space-y-3">
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-wider">Kriteria
                                Tambahan - Kost</label>
                            <div class="grid grid-cols-3 gap-2">
                                <label class="cursor-pointer text-center">
                                    <input type="radio" name="tipe_kos" value="Pria" {{ request('tipe_kos') == 'Pria' ? 'checked' : '' }} class="peer hidden">
                                    <div
                                        class="py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-600 hover:bg-slate-50 transition">
                                        Pria</div>
                                </label>
                                <label class="cursor-pointer text-center">
                                    <input type="radio" name="tipe_kos" value="Wanita" {{ request('tipe_kos') == 'Wanita' ? 'checked' : '' }} class="peer hidden">
                                    <div
                                        class="py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-600 hover:bg-slate-50 transition">
                                        Wanita</div>
                                </label>
                                <label class="cursor-pointer text-center">
                                    <input type="radio" name="tipe_kos" value="Campur" {{ request('tipe_kos') == 'Campur' ? 'checked' : '' }} class="peer hidden">
                                    <div
                                        class="py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-600 hover:bg-slate-50 transition">
                                        Campur</div>
                                </label>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-wider">Batas Harga
                                Maksimal</label>
                            <select name="harga_maksimal"
                                class="w-full h-11 px-3 bg-slate-50 border border-slate-200 rounded-xl outline-none text-xs text-slate-600 focus:border-blue-500 focus:bg-white transition">
                                <option value="">Semua Harga</option>
                                <option value="500000" {{ request('harga_maksimal') == '500000' ? 'selected' : '' }}>Di bawah
                                    Rp 500 Ribu</option>
                                <option value="1000000" {{ request('harga_maksimal') == '1000000' ? 'selected' : '' }}>Di
                                    bawah Rp 1 Juta</option>
                                <option value="1500000" {{ request('harga_maksimal') == '1500000' ? 'selected' : '' }}>Di
                                    bawah Rp 1.5 Juta</option>
                                <option value="2000000" {{ request('harga_maksimal') == '2000000' ? 'selected' : '' }}>Di
                                    bawah Rp 2 Juta</option>
                            </select>
                        </div>

                        <div class="space-y-3">
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-wider">Fasilitas
                                Properti</label>
                            <div class="grid grid-cols-1 gap-2">
                                <label
                                    class="flex items-center justify-between p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 text-xs font-bold text-slate-700">
                                    <span class="flex items-center gap-2">❄️ AC Utama</span>
                                    <input type="checkbox" name="ac" {{ request()->has('ac') ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-500 rounded border-slate-300 focus:ring-blue-500">
                                </label>
                                <label
                                    class="flex items-center justify-between p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 text-xs font-bold text-slate-700">
                                    <span class="flex items-center gap-2">🌐 Wifi / Internet</span>
                                    <input type="checkbox" name="wifi" {{ request()->has('wifi') ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-500 rounded border-slate-300 focus:ring-blue-500">
                                </label>
                                <label
                                    class="flex items-center justify-between p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 text-xs font-bold text-slate-700">
                                    <span class="flex items-center gap-2">🚿 Kamar Mandi Dalam</span>
                                    <input type="checkbox" name="kamar_mandi_dalam" {{ request()->has('kamar_mandi_dalam') ? 'checked' : '' }}
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

    <div class="text-center mb-10">
        <h2 class="text-xl font-bold text-slate-800 tracking-wide inline-block border-b-2 border-slate-800 pb-1">
            Temukan Kos Impian Anda
        </h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse ($semuaKos as $kos)
            <div
                class="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-md transition duration-300 flex flex-col">
                <div class="bg-slate-200 h-48 w-full flex items-center justify-center text-slate-400 font-bold tracking-wide">
                    Gambar Kos
                </div>

                <div class="p-5 border-b border-slate-100 flex-grow space-y-2 text-left">
                    <div class="flex items-center justify-between">
                        <span
                            class="px-2.5 py-0.5 rounded-md text-[10px] font-extrabold uppercase tracking-wider 
                                    {{ $kos->tipe_kos == 'Pria' ? 'bg-blue-50 text-blue-600' : ($kos->tipe_kos == 'Wanita' ? 'bg-pink-50 text-pink-600' : 'bg-purple-50 text-purple-600') }}">
                            {{ $kos->tipe_kos }}
                        </span>
                        <span class="text-xs font-bold text-slate-400">{{ $kos->lokasi }}</span>
                    </div>

                    <h3 class="font-bold text-base text-slate-800">{{ $kos->nama }}</h3>
                    <p class="text-xs text-slate-500 font-medium leading-relaxed">{{ $kos->alamat }}</p>
                    <p class="text-sm font-black text-blue-600">Rp {{ number_format($kos->harga, 0, ',', '.') }} <span
                            class="text-[10px] text-slate-400 font-medium">/ bulan</span></p>

                    <div class="flex gap-2 pt-1 text-[10px] font-semibold text-slate-400">
                        @if($kos->ac) <span>❄️ AC</span> @endif
                        @if($kos->wifi) <span>🌐 Wifi</span> @endif
                        @if($kos->kamar_mandi_dalam) <span>🚿 K.Mandi</span> @endif
                    </div>
                </div>

                <div class="p-4 bg-slate-50">
                    <a href="{{ route('kos.show', $kos->id) }}"
                        class="block text-center bg-blue-500 hover:bg-blue-600 text-white font-bold py-2.5 rounded-xl transition text-xs tracking-wider uppercase">
                        Lihat Detail Kos
                    </a>
                </div>
            </div>
        @empty
            <div
                class="col-span-1 md:col-span-3 bg-white p-12 rounded-2xl border border-dashed border-slate-200 text-center text-sm text-slate-400 font-medium">
                Tidak ada kos yang cocok dengan kriteria pencarian Anda.
            </div>
        @endforelse
    </div>

    <script>
        const btnOpenFilter = document.getElementById('btnOpenFilter');
        const btnCloseFilter = document.getElementById('btnCloseFilter');
        const filterModal = document.getElementById('filterModal');
        const btnResetModal = document.getElementById('btnResetModal');

        // 1. Fungsi Buka Modal
        btnOpenFilter.addEventListener('click', () => {
            filterModal.classList.remove('hidden');
        });

        // 2. Fungsi Tutup Modal
        btnCloseFilter.addEventListener('click', () => {
            filterModal.classList.add('hidden');
        });

        // Close modal jika area luar kotak putih diklik
        filterModal.addEventListener('click', (e) => {
            if (e.target === filterModal) {
                filterModal.classList.add('hidden');
            }
        });

        // 3. Fungsi Reset Pilihan di Dalam Modal
        btnResetModal.addEventListener('click', () => {
            document.querySelectorAll('input[name="tipe_kos"]').forEach(radio => radio.checked = false);
            document.querySelector('select[name="harga_maksimal"]').value = "";
            document.querySelectorAll('#filterModal input[type="checkbox"]').forEach(cb => cb.checked = false);
        });
    </script>
@endsection