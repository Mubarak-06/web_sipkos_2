@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6 text-left relative">

        <div class="lg:col-span-2 space-y-6">
            <a href="{{ route('kos.show', $kos->id) }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-blue-600 transition">
                &larr; Kembali ke Detail Kos
            </a>

            <div
                class="bg-slate-200 h-72 rounded-2xl flex items-center justify-center text-slate-400 font-bold border border-slate-300 shadow-sm text-base tracking-wide">
                Gambar Fasilitas Utama
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <h3 class="font-bold text-base text-slate-800">Layanan Bantuan Pindahan</h3>

                <label
                    class="flex items-center justify-between p-4 bg-slate-50 rounded-xl cursor-pointer border border-slate-200 hover:bg-slate-100 transition duration-200">
                    <div class="space-y-0.5">
                        <p class="text-sm font-bold text-slate-700">Tambah Armada / Jasa Pindahan</p>
                        <p class="text-xs text-slate-400 font-medium">Bantuan pengangkutan barang langsung ke lokasi kos
                            baru oleh tim kami.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-black text-blue-600">+ Rp 100.000</span>
                        <input type="checkbox" id="checkJasaPindah"
                            class="w-5 h-5 text-blue-500 rounded border-slate-300 focus:ring-blue-500">
                    </div>
                </label>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-xl space-y-5 sticky top-6">
                <h3 class="text-lg font-black text-slate-800 tracking-wide">Booking Kos</h3>

                <form id="formBookingReal">
                    @csrf
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Pilih Tanggal
                            Check-in</label>
                        <input type="date" name="tanggal_checkin" required value="2026-06-01"
                            class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold outline-none focus:border-blue-500 focus:bg-white transition">
                    </div>

                    <div class="space-y-1.5 pt-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Pilih Durasi
                            Sewa</label>
                        <div class="grid grid-cols-2 gap-2" id="durasiContainer">
                            <button type="button" data-durasi="1"
                                class="durasi-btn py-2.5 border-2 border-blue-500 bg-blue-50 text-blue-600 font-bold rounded-xl text-xs tracking-wide">1
                                Bulan</button>
                            <button type="button" data-durasi="3"
                                class="durasi-btn py-2.5 border border-slate-200 text-slate-600 font-bold rounded-xl text-xs hover:border-blue-300">3
                                Bulan</button>
                            <button type="button" data-durasi="6"
                                class="durasi-btn py-2.5 border border-slate-200 text-slate-600 font-bold rounded-xl text-xs hover:border-blue-300">6
                                Bulan</button>
                            <button type="button" data-durasi="12"
                                class="durasi-btn py-2.5 border border-slate-200 text-slate-600 font-bold rounded-xl text-xs hover:border-blue-300">12
                                Bulan</button>
                        </div>
                        <input type="hidden" name="durasi_sewa" id="inputDurasi" value="1">
                    </div>

                    <div class="border-t border-slate-100 pt-4 mt-4 space-y-2 text-xs font-semibold text-slate-500">
                        <div class="flex justify-between">
                            <span>Durasi</span>
                            <span class="font-bold text-slate-800" id="displayDurasiText">1 Bulan</span>
                        </div>
                        <div class="flex justify-between"><span>Harga Sewa ({{ $kos->tipe_kos }})</span><span
                                class="font-bold text-slate-800">Rp {{ number_format($kos->harga, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-between text-blue-600 font-bold" id="rowJasaPindah" style="display: none;">
                            <span>Layanan Pindahan</span><span>Rp 100.000</span>
                        </div>

                        <div
                            class="flex justify-between text-sm font-black text-slate-800 pt-3 border-t border-dashed border-slate-200">
                            <span>Total Bayar</span>
                            <span id="txtTotalBayar" data-harga-asli="{{ $kos->harga }}">Rp
                                {{ number_format($kos->harga, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <input type="hidden" id="inputTotalHarga" name="total_harga_input" value="{{ $kos->harga }}">

                    <button type="submit"
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-blue-100 transition mt-6 text-sm tracking-wide">
                        Booking
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div id="modalPemilik"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
        <div
            class="bg-white w-full max-w-sm rounded-[2rem] shadow-2xl p-8 text-center space-y-6 border border-slate-100 animate-fade-in">
            <h3 class="text-base font-black text-slate-800 border-b pb-3 tracking-wide uppercase">Detail Booking</h3>

            <div class="space-y-4">
                <div
                    class="w-16 h-16 bg-slate-100 mx-auto rounded-full flex items-center justify-center text-slate-400 text-[10px] font-black border border-slate-200">
                    OWNER
                </div>
                <div>
                    <h4 class="font-black text-slate-800 text-sm tracking-wide uppercase">Konfirmasi Pemilik Kos</h4>
                    <p class="text-xs text-slate-400 font-medium mt-1">Kamar ini memerlukan validasi manual dari pemilik
                        kos.</p>
                </div>
                <p class="text-xs font-black text-slate-700 bg-slate-50 py-2.5 rounded-xl border border-slate-100 tracking-wider"
                    id="txtNoWa">
                    Loading...
                </p>
            </div>

            <a href="#" id="btnHubungiWa" target="_blank"
                class="block w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 rounded-xl shadow-md transition text-xs tracking-wide">
                HUBUNGI VIA WHATSAPP
            </a>

            <a href="{{ route('my.bookings') }}"
                class="block text-[11px] font-bold text-slate-400 hover:text-slate-600 transition pt-1">
                &larr; Lewati & Lihat Detail di My Bookings
            </a>
        </div>
    </div>

    <script>
        const checkJasaPindah = document.getElementById('checkJasaPindah');
        const rowJasaPindah = document.getElementById('rowJasaPindah');
        const txtTotalBayar = document.getElementById('txtTotalBayar');
        const inputTotalHarga = document.getElementById('inputTotalHarga');
        const formBookingReal = document.getElementById('formBookingReal');
        const modalPemilik = document.getElementById('modalPemilik');

        // Elemen Durasi
        const durasiButtons = document.querySelectorAll('.durasi-btn');
        const displayDurasiText = document.getElementById('displayDurasiText'); // Pastikan ID ini ada di HTML Anda

        // State
        let durasiAktif = 1;
        const hargaKosAsli = parseInt(txtTotalBayar.getAttribute('data-harga-asli'));

        // Fungsi Hitung Total
        function hitungTotal() {
            const biayaJasaPindah = checkJasaPindah.checked ? 100000 : 0;
            const total = (hargaKosAsli * durasiAktif) + biayaJasaPindah;

            txtTotalBayar.innerText = "Rp " + total.toLocaleString('id-ID');
            inputTotalHarga.value = total;
        }

        // 1. Logika Klik Durasi
        durasiButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                // Update UI Tombol
                durasiButtons.forEach(b => {
                    b.classList.remove('border-blue-500', 'bg-blue-50', 'text-blue-600');
                    b.classList.add('border-slate-200', 'text-slate-600');
                });
                this.classList.add('border-blue-500', 'bg-blue-50', 'text-blue-600');
                this.classList.remove('border-slate-200');

                // Update State
                durasiAktif = parseInt(this.getAttribute('data-durasi'));
                displayDurasiText.innerText = durasiAktif + " Bulan";

                hitungTotal();
            });
        });

        // 2. Logika Jasa Pindahan
        checkJasaPindah.addEventListener('change', function () {
            rowJasaPindah.style.display = this.checked ? 'flex' : 'none';
            hitungTotal();
        });

        // 3. Kirim Form via Fetch AJAX
        formBookingReal.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('durasi', durasiAktif); // Kirim durasi ke server

            fetch("{{ route('booking.store', $kos->id) }}", {
                method: "POST",
                body: formData,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        let rawPhone = data.no_wa.toString().replace(/[-+\s]/g, '');
                        if (rawPhone.startsWith('0')) rawPhone = '62' + rawPhone.slice(1);

                        document.getElementById('txtNoWa').innerText = "WhatsApp Pemilik: +" + rawPhone;
                        const templatePesan = encodeURIComponent(`Halo Pemilik Kos, saya ingin booking "${'{{ $kos->nama }}'}" untuk durasi ${durasiAktif} bulan. Mohon validasinya.`);
                        document.getElementById('btnHubungiWa').href = "https://wa.me/" + rawPhone + "?text=" + templatePesan;

                        modalPemilik.classList.remove('hidden');
                    }
                })
                .catch(err => console.error("Error:", err));
        });
    </script>
@endsection