@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6 text-left relative">

        <div class="lg:col-span-2 space-y-6">
            <a href="{{ route('kos.show', $kos->id) }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-blue-600 transition">
                &larr; Kembali ke Detail Kos
            </a>

            <div
                class="bg-slate-100 h-72 md:h-96 rounded-2xl flex items-center justify-center border border-slate-200 shadow-sm relative overflow-hidden group">
                <div class="w-full h-full flex items-center justify-center">
                    @if($kos->foto)
                        <img id="sliderImage" src="{{ asset('foto-kos/' . $kos->foto) }}"
                            class="w-full h-full object-cover object-center transition duration-300" alt="Foto Kos">
                    @else
                        <div class="text-center text-slate-400">
                            <span class="block text-4xl mb-1">🏠</span>
                            <span class="text-xs font-semibold">Gambar Fasilitas Utama Tidak Tersedia</span>
                        </div>
                    @endif
                </div>

                @if($kos->foto_2 || $kos->foto_3)
                    <button type="button" onclick="changeSlide(-1)"
                        class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-slate-800 w-9 h-9 rounded-full shadow flex items-center justify-center font-black transition select-none z-10">
                        &#10094;
                    </button>

                    <button type="button" onclick="changeSlide(1)"
                        class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-slate-800 w-9 h-9 rounded-full shadow flex items-center justify-center font-black transition select-none z-10">
                        &#10095;
                    </button>
                @endif
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <h3 class="font-bold text-base text-slate-800">
                    Layanan Bantuan Pindahan
                </h3>

                <label
                    class="flex items-center justify-between p-4 bg-slate-50 rounded-xl cursor-pointer border border-slate-200">
                    <div>
                        <p class="text-sm font-bold text-slate-700">
                            Gunakan Jasa Pindahan
                        </p>

                        <p class="text-xs text-slate-400">
                            Pilih layanan pindahan yang tersedia.
                        </p>
                    </div>

                    <input type="checkbox" id="checkJasaPindah" class="w-5 h-5 text-blue-500 rounded">
                </label>

                <div id="pilihanJasaContainer" class="hidden">
                    <label class="block text-sm font-semibold text-slate-600 mb-2">
                        Pilih Jasa
                    </label>

                    <select id="pilihanJasa"
                        class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-blue-500">
                        <option value="">-- Pilih Jasa --</option>
                        <option value="Jasa Pindahan SIPKOS">Jasa Pindahan SIPKOS</option>
                        <option value="Jasa Pindahan Express">Jasa Pindahan Express</option>
                        <option value="Jasa Angkut Motor">Jasa Angkut Motor</option>
                        <option value="Jasa Pick Up">Jasa Pick Up</option>
                    </select>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <h3 class="font-bold text-base text-slate-800 mb-3">
                    Informasi Pembayaran
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4">
                        <p class="font-black text-slate-800 mb-1">QRIS</p>
                        <p class="text-xs text-slate-400">Scan QRIS admin SIPKOS, lalu upload bukti pembayaran.</p>
                    </div>

                    <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4">
                        <p class="font-black text-slate-800 mb-1">DANA</p>
                        <p class="text-xs text-slate-400">Transfer ke nomor DANA admin.</p>
                    </div>

                    <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4">
                        <p class="font-black text-slate-800 mb-1">Rekening Bank</p>
                        <p class="text-xs text-slate-400">Transfer ke rekening admin SIPKOS.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-xl space-y-5 sticky top-6">
                <h3 class="text-lg font-black text-slate-800 tracking-wide">
                    Booking Kos
                </h3>

                <form id="formBookingReal" enctype="multipart/form-data">
                    @csrf

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-wider">
                            Pilih Tanggal Check-in
                        </label>

                        <input type="date" name="tanggal_checkin" required min="{{ date('Y-m-d') }}"
                            value="{{ date('Y-m-d') }}"
                            class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold outline-none focus:border-blue-500 focus:bg-white transition">
                    </div>

                    <div class="space-y-1.5 pt-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-wider">
                            Pilih Durasi Sewa
                        </label>

                        <div class="grid grid-cols-2 gap-2" id="durasiContainer">
                            <button type="button" data-durasi="1"
                                class="durasi-btn py-2.5 border-2 border-blue-500 bg-blue-50 text-blue-600 font-bold rounded-xl text-xs tracking-wide">
                                1 Bulan
                            </button>

                            <button type="button" data-durasi="3"
                                class="durasi-btn py-2.5 border border-slate-200 text-slate-600 font-bold rounded-xl text-xs hover:border-blue-300">
                                3 Bulan
                            </button>

                            <button type="button" data-durasi="6"
                                class="durasi-btn py-2.5 border border-slate-200 text-slate-600 font-bold rounded-xl text-xs hover:border-blue-300">
                                6 Bulan
                            </button>

                            <button type="button" data-durasi="12"
                                class="durasi-btn py-2.5 border border-slate-200 text-slate-600 font-bold rounded-xl text-xs hover:border-blue-300">
                                12 Bulan
                            </button>
                        </div>

                        <input type="hidden" name="durasi" id="inputDurasi" value="1">
                    </div>

                    <div class="border-t border-slate-100 pt-4 space-y-2 text-xs font-bold text-slate-600">
                        <div class="flex justify-between">
                            <span>Durasi Terpilih:</span>
                            <span id="displayDurasiText" class="text-slate-800">1 Bulan</span>
                        </div>

                        <div class="flex justify-between" id="rowJasaPindah" style="display: none;">
                            <span>Layanan Pindahan:</span>
                            <span class="text-blue-600">+ Rp 100.000</span>
                        </div>

                        <div class="border-t border-slate-100 pt-3 mt-2 flex justify-between items-center">
                            <span class="text-slate-400 text-[11px]">Total Pembayaran:</span>
                            <span id="txtTotalBayar" class="text-xl font-black text-blue-600"
                                data-harga-asli="{{ $kos->harga }}">
                                Rp {{ number_format($kos->harga, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <input type="hidden" id="inputTotalHarga" name="total_harga_input" value="{{ $kos->harga }}">

                    <div class="border-t border-slate-100 pt-4 mt-4 space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-wider">
                            Metode Pembayaran
                        </label>

                        <div class="grid grid-cols-3 gap-2">
                            <label class="cursor-pointer">
                                <input type="radio" name="metode_pembayaran" value="qris" class="peer hidden" checked>
                                <div
                                    class="text-center py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 peer-checked:bg-blue-50 peer-checked:border-blue-500 peer-checked:text-blue-600">
                                    QRIS
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="metode_pembayaran" value="dana" class="peer hidden">
                                <div
                                    class="text-center py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 peer-checked:bg-blue-50 peer-checked:border-blue-500 peer-checked:text-blue-600">
                                    DANA
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="metode_pembayaran" value="transfer_bank" class="peer hidden">
                                <div
                                    class="text-center py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 peer-checked:bg-blue-50 peer-checked:border-blue-500 peer-checked:text-blue-600">
                                    Bank
                                </div>
                            </label>
                        </div>

                        <div
                            class="bg-slate-50 border border-slate-100 rounded-2xl p-4 text-xs text-slate-600 font-semibold space-y-2">
                            <div id="paymentQris">
                                <p class="font-black text-slate-800 mb-2">Pembayaran QRIS</p>

                                <div class="bg-white border border-slate-200 rounded-xl p-3 text-center">
                                    <img src="{{ asset('payment/qris-sipkos.png') }}"
                                        onerror="this.style.display='none'; document.getElementById('qrisFallback').classList.remove('hidden');"
                                        class="w-40 h-40 object-contain mx-auto">

                                    <div id="qrisFallback" class="hidden text-slate-400 py-8">
                                        QRIS belum tersedia.<br>
                                        Simpan gambar di public/payment/qris-sipkos.png
                                    </div>
                                </div>

                                <p class="text-slate-400 mt-2">
                                    Scan QRIS, lalu upload bukti pembayaran.
                                </p>
                            </div>

                            <div id="paymentDana" class="hidden">
                                <p class="font-black text-slate-800">Pembayaran DANA</p>
                                <p>Nomor DANA Admin:</p>
                                <p class="text-blue-600 font-black text-base">085650816792</p>
                                <p class="text-slate-400">
                                    Transfer sesuai total pembayaran, lalu upload bukti.
                                </p>
                            </div>

                            <div id="paymentBank" class="hidden">
                                <p class="font-black text-slate-800">Transfer Rekening Admin</p>
                                <p>Bank BRI</p>
                                <p class="text-blue-600 font-black text-base">1234567890</p>
                                <p>a.n Admin SIPKOS</p>
                                <p class="text-slate-400">
                                    Nomor rekening bisa diganti sesuai rekening admin.
                                </p>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-wider">
                                Upload Bukti Pembayaran
                            </label>

                            <input type="file" name="bukti_pembayaran" id="buktiPembayaran" accept="image/*" required
                                class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100">
                        </div>
                    </div>

                    <button type="button" id="btnPemicuKonfirmasi"
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-blue-100 transition mt-6 text-sm tracking-wide">
                        Booking Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div id="modalKonfirmasi"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
        <div
            class="bg-white w-full max-w-sm rounded-[2rem] shadow-2xl p-6 text-center space-y-4 border border-slate-100 animate-fade-in">
            <span class="text-3xl">⚠️</span>

            <h3 class="text-base font-black text-slate-800 tracking-wide uppercase">
                Konfirmasi Booking
            </h3>

            <p class="text-xs text-slate-400 font-medium">
                Periksa kembali ringkasan booking dan pembayaran sebelum melanjutkan.
            </p>

            <div class="bg-slate-50 p-4 rounded-2xl text-left text-xs space-y-2 font-bold text-slate-600">
                <div class="flex justify-between">
                    <span>Durasi Sewa:</span>
                    <span id="confDurasi" class="text-slate-800">1 Bulan</span>
                </div>

                <div class="flex justify-between">
                    <span>Jasa Pindahan:</span>
                    <span id="confPindah" class="text-slate-800">Tidak</span>
                </div>

                <div class="flex justify-between">
                    <span>Metode Bayar:</span>
                    <span id="confMetodePembayaran" class="text-slate-800">QRIS</span>
                </div>

                <div class="flex justify-between text-blue-600 font-black border-t pt-2 mt-1">
                    <span>Total Bayar:</span>
                    <span id="confTotal">Rp 0</span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 pt-2">
                <button type="button" id="btnBatalKonfirmasi"
                    class="py-2.5 bg-slate-100 text-slate-500 font-bold rounded-xl text-xs transition">
                    Batal
                </button>

                <button type="button" id="btnKirimBookingFix"
                    class="py-2.5 bg-blue-500 text-white font-bold rounded-xl text-xs shadow-md transition">
                    Ya, Kirim
                </button>
            </div>
        </div>
    </div>

    <div id="modalPemilik"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
        <div
            class="bg-white w-full max-w-sm rounded-[2rem] shadow-2xl p-8 text-center space-y-6 border border-slate-100 animate-fade-in">
            <h3 class="text-base font-black text-slate-800 border-b pb-3 tracking-wide uppercase">
                Detail Booking
            </h3>

            <div class="space-y-4">
                <div
                    class="w-16 h-16 bg-slate-100 mx-auto rounded-full flex items-center justify-center text-slate-400 text-[10px] font-black border border-slate-200">
                    OWNER
                </div>

                <div>
                    <h4 class="font-black text-slate-800 text-sm tracking-wide uppercase">
                        Booking Berhasil Dibuat
                    </h4>

                    <p class="text-xs text-slate-400 font-medium mt-1">
                        Pembayaran menunggu verifikasi admin. Silakan hubungi pemilik kos.
                    </p>
                </div>

                <p id="txtKodeBooking"
                    class="text-xs font-black text-blue-700 bg-blue-50 py-2.5 rounded-xl border border-blue-100 tracking-wider">
                    Kode Booking: Loading...
                </p>

                <p id="txtStatusPembayaran"
                    class="text-xs font-black text-yellow-700 bg-yellow-50 py-2.5 rounded-xl border border-yellow-100 tracking-wider">
                    Status: Menunggu Verifikasi Admin
                </p>

                <p class="text-xs font-black text-slate-700 bg-slate-50 py-2.5 rounded-xl border border-slate-100 tracking-wider"
                    id="txtNoWa">
                    Loading...
                </p>

                <p id="txtNoWaPindahan"
                    class="hidden text-xs font-black text-green-700 bg-green-50 py-2.5 rounded-xl border border-green-100 tracking-wider">
                    WhatsApp Jasa Pindahan: +6285650816792
                </p>
            </div>

            <a href="#" id="btnHubungiWa" target="_blank"
                class="block w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 rounded-xl shadow-md transition text-xs tracking-wide">
                HUBUNGI VIA WHATSAPP
            </a>

            <a href="#" id="btnHubungiJasa" target="_blank"
                class="hidden block w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-xl shadow-md transition text-xs tracking-wide">
                HUBUNGI JASA PINDAHAN
            </a>

            <a href="{{ route('booking.pdf') }}"
                class="block w-full bg-red-500 hover:bg-red-600 text-white font-bold py-3 rounded-xl shadow-md transition text-xs tracking-wide">
                DOWNLOAD PDF
            </a>

            <a href="{{ route('my.bookings') }}"
                class="block text-[11px] font-bold text-slate-400 hover:text-slate-600 transition pt-1">
                &larr; Lewati & Lihat Detail di My Bookings
            </a>
        </div>
    </div>

    <script>
        const imageList = [
            "{{ $kos->foto ? asset('foto-kos/' . $kos->foto) : '' }}",
            "{{ $kos->foto_2 ? asset('foto-kos/' . $kos->foto_2) : '' }}",
            "{{ $kos->foto_3 ? asset('foto-kos/' . $kos->foto_3) : '' }}"
        ].filter(img => img !== "");

        let currentSlideIndex = 0;
        const sliderImageElement = document.getElementById('sliderImage');

        function changeSlide(direction) {
            if (imageList.length === 0) return;

            currentSlideIndex += direction;

            if (currentSlideIndex >= imageList.length) {
                currentSlideIndex = 0;
            }

            if (currentSlideIndex < 0) {
                currentSlideIndex = imageList.length - 1;
            }

            if (sliderImageElement) {
                sliderImageElement.src = imageList[currentSlideIndex];
            }
        }

        const checkJasaPindah = document.getElementById('checkJasaPindah');
        const rowJasaPindah = document.getElementById('rowJasaPindah');
        const txtTotalBayar = document.getElementById('txtTotalBayar');
        const inputTotalHarga = document.getElementById('inputTotalHarga');
        const inputDurasi = document.getElementById('inputDurasi');
        const formBookingReal = document.getElementById('formBookingReal');
        const pilihanJasaContainer = document.getElementById('pilihanJasaContainer');
        const pilihJasa = document.getElementById('pilihanJasa');

        const modalKonfirmasi = document.getElementById('modalKonfirmasi');
        const modalPemilik = document.getElementById('modalPemilik');
        const btnPemicuKonfirmasi = document.getElementById('btnPemicuKonfirmasi');
        const btnBatalKonfirmasi = document.getElementById('btnBatalKonfirmasi');
        const btnKirimBookingFix = document.getElementById('btnKirimBookingFix');

        const durasiButtons = document.querySelectorAll('.durasi-btn');
        const displayDurasiText = document.getElementById('displayDurasiText');

        const paymentRadios = document.querySelectorAll('input[name="metode_pembayaran"]');
        const paymentQris = document.getElementById('paymentQris');
        const paymentDana = document.getElementById('paymentDana');
        const paymentBank = document.getElementById('paymentBank');
        const buktiPembayaran = document.getElementById('buktiPembayaran');

        let durasiAktif = 1;
        const hargaKosAsli = parseInt(txtTotalBayar.getAttribute('data-harga-asli'));

        function formatRupiah(angka) {
            return "Rp " + angka.toLocaleString('id-ID');
        }

        function getMetodePembayaran() {
            return document.querySelector('input[name="metode_pembayaran"]:checked').value;
        }

        function getNamaMetodePembayaran() {
            const metode = getMetodePembayaran();

            if (metode === 'dana') {
                return 'DANA';
            }

            if (metode === 'transfer_bank') {
                return 'Transfer Bank';
            }

            return 'QRIS';
        }

        function hitungTotal() {
            const biayaJasaPindah = checkJasaPindah.checked ? 100000 : 0;
            const total = (hargaKosAsli * durasiAktif) + biayaJasaPindah;

            txtTotalBayar.innerText = formatRupiah(total);
            inputTotalHarga.value = total;
            inputDurasi.value = durasiAktif;
        }

        function updatePaymentInfo() {
            const metode = getMetodePembayaran();

            paymentQris.classList.add('hidden');
            paymentDana.classList.add('hidden');
            paymentBank.classList.add('hidden');

            if (metode === 'qris') {
                paymentQris.classList.remove('hidden');
            }

            if (metode === 'dana') {
                paymentDana.classList.remove('hidden');
            }

            if (metode === 'transfer_bank') {
                paymentBank.classList.remove('hidden');
            }
        }

        paymentRadios.forEach(radio => {
            radio.addEventListener('change', updatePaymentInfo);
        });

        updatePaymentInfo();

        durasiButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                durasiButtons.forEach(b => {
                    b.classList.remove('border-2', 'border-blue-500', 'bg-blue-50', 'text-blue-600', 'tracking-wide');
                    b.classList.add('border', 'border-slate-200', 'text-slate-600');
                });

                this.classList.remove('border', 'border-slate-200', 'text-slate-600');
                this.classList.add('border-2', 'border-blue-500', 'bg-blue-50', 'text-blue-600', 'tracking-wide');

                durasiAktif = parseInt(this.getAttribute('data-durasi'));
                displayDurasiText.innerText = durasiAktif + " Bulan";

                hitungTotal();
            });
        });

        checkJasaPindah.addEventListener('change', function () {
            rowJasaPindah.style.display = this.checked ? 'flex' : 'none';

            pilihanJasaContainer.classList.toggle('hidden', !this.checked);

            if (!this.checked) {
                pilihJasa.value = "";
            }

            hitungTotal();
        });

        btnPemicuKonfirmasi.addEventListener('click', function () {
            const tanggalCheckin = formBookingReal.querySelector('input[name="tanggal_checkin"]').value;

            if (!tanggalCheckin) {
                alert('Silakan pilih tanggal check-in terlebih dahulu.');
                return;
            }

            if (checkJasaPindah.checked && !pilihJasa.value) {
                alert('Silakan pilih jasa pindahan terlebih dahulu.');
                return;
            }

            if (!buktiPembayaran.files.length) {
                alert('Silakan upload bukti pembayaran terlebih dahulu.');
                return;
            }

            document.getElementById('confDurasi').innerText = durasiAktif + " Bulan";
            document.getElementById('confPindah').innerText = checkJasaPindah.checked ? pilihJasa.value : "Tidak";
            document.getElementById('confTotal').innerText = txtTotalBayar.innerText;
            document.getElementById('confMetodePembayaran').innerText = getNamaMetodePembayaran();

            modalKonfirmasi.classList.remove('hidden');
        });

        btnBatalKonfirmasi.addEventListener('click', function () {
            modalKonfirmasi.classList.add('hidden');
        });

        btnKirimBookingFix.addEventListener('click', function () {
            if (checkJasaPindah.checked && !pilihJasa.value) {
                alert('Silakan pilih jasa pindahan terlebih dahulu.');
                return;
            }

            if (!buktiPembayaran.files.length) {
                alert('Silakan upload bukti pembayaran terlebih dahulu.');
                return;
            }

            btnKirimBookingFix.disabled = true;
            btnKirimBookingFix.innerText = 'Memproses...';

            modalKonfirmasi.classList.add('hidden');

            const formData = new FormData(formBookingReal);

            formData.set('durasi', durasiAktif);
            formData.set('jasa_pindahan', checkJasaPindah.checked ? 1 : 0);
            formData.set('nama_jasa_pindahan', checkJasaPindah.checked ? pilihJasa.value : '');

            fetch("{{ route('booking.store', $kos->id) }}", {
                method: "POST",
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(async res => {
                    const data = await res.json().catch(() => ({}));

                    if (!res.ok) {
                        let pesan = 'Terjadi kesalahan saat menyimpan booking.';

                        if (data.errors) {
                            pesan = Object.values(data.errors).flat().join('\n');
                        } else if (data.message) {
                            pesan = data.message;
                        }

                        alert(pesan);
                        throw new Error(pesan);
                    }

                    return data;
                })
                .then(data => {
                    if (data.status === 'success') {
                        let rawPhone = data.no_wa.toString().replace(/[-+\s]/g, '');

                        if (rawPhone.startsWith('0')) {
                            rawPhone = '62' + rawPhone.slice(1);
                        }

                        document.getElementById('txtKodeBooking').innerText =
                            "Kode Booking: " + data.kode_booking;

                        document.getElementById('txtStatusPembayaran').innerText =
                            "Status Pembayaran: " + data.status_pembayaran;

                        document.getElementById('txtNoWa').innerText =
                            "WhatsApp Pemilik: +" + rawPhone;

                        const templatePesanPemilik = encodeURIComponent(
                            `Halo Pemilik Kos, saya sudah booking "{{ $kos->nama }}" dengan kode booking ${data.kode_booking}. Metode pembayaran: ${getNamaMetodePembayaran()}. Mohon validasinya.`
                        );

                        document.getElementById('btnHubungiWa').href =
                            "https://wa.me/" + rawPhone + "?text=" + templatePesanPemilik;

                        const jasaPindahanWA = "6285650816792";
                        const txtNoWaPindahan = document.getElementById('txtNoWaPindahan');
                        const btnHubungiJasa = document.getElementById('btnHubungiJasa');

                        if (checkJasaPindah.checked) {
                            txtNoWaPindahan.classList.remove('hidden');

                            const templatePesanJasa = encodeURIComponent(
                                `Halo, saya membutuhkan layanan "${pilihJasa.value}" untuk membantu pindahan ke kos "{{ $kos->nama }}". Kode booking saya ${data.kode_booking}.`
                            );

                            btnHubungiJasa.href =
                                "https://wa.me/" + jasaPindahanWA + "?text=" + templatePesanJasa;

                            btnHubungiJasa.classList.remove('hidden');
                        } else {
                            txtNoWaPindahan.classList.add('hidden');
                            btnHubungiJasa.classList.add('hidden');
                        }

                        modalPemilik.classList.remove('hidden');
                    }
                })
                .catch(err => {
                    console.error("Error:", err);
                })
                .finally(() => {
                    btnKirimBookingFix.disabled = false;
                    btnKirimBookingFix.innerText = 'Ya, Kirim';
                });
        });

        hitungTotal();
    </script>
@endsection