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

            <form action="{{ route('kos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
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
                    class="bg-blue-500 hover:bg-blue-600 text-white text-xs font-bold px-6 py-2.5 rounded-xl shadow-md transition">
                    Simpan Properti
                </button>
            </form>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <h4 class="text-lg font-bold text-slate-800 mb-4">
                Daftar Kos Aktif
            </h4>

            <table id="tabelKos" class="display responsive nowrap w-full">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nama Kos</th>
                        <th>Lokasi</th>
                        <th>Tipe</th>
                        <th>Harga</th>
                        <th>Fasilitas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($semuaKos as $kos)
                        <tr>

                            <td>
                                @if($kos->foto)
                                    <img src="{{ asset('foto-kos/' . $kos->foto) }}" width="60" class="rounded">
                                @endif
                            </td>

                            <td>{{ $kos->nama }}</td>

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
                            </td>

                            <td>
                                <div class="flex gap-2">

                                    <a href="{{ route('kos.edit', $kos->id) }}"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">
                                        Edit
                                    </a>

                                    <form action="{{ route('kos.destroy', $kos->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin hapus data?')">

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
        <div class="mt-10">
            <h4 class="text-sm font-bold text-slate-800 mb-3">
                Data Booking Masuk
            </h4>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-slate-100">
                        <tr>
                            <th class="p-3 text-left">Kos</th>
                            <th class="p-3 text-left">Check In</th>
                            <th class="p-3 text-left">Durasi</th>
                            <th class="p-3 text-left">Jasa Pindahan</th>
                            <th class="p-3 text-left">Total</th>
                            <th class="p-3 text-left">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($semuaBooking as $booking)
                            <tr class="border-t">
                                <td class="p-3">
                                    {{ $booking->kos->nama }}
                                </td>

                                <td class="p-3">
                                    {{ $booking->tanggal_checkin }}
                                </td>

                                <td class="p-3">
                                    {{ $booking->durasi }} Bulan
                                </td>

                                <td class="p-3">
                                    @if($booking->jasa_pindahan)
                                        {{ $booking->nama_jasa_pindahan }}
                                    @else
                                        Tidak
                                    @endif
                                </td>

                                <td class="p-3">
                                    Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                                </td>

                                <td class="p-3">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded">
                                        Booking Masuk
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-4 text-center text-slate-400">
                                    Belum ada booking.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
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

        });
    </script>
@endsection