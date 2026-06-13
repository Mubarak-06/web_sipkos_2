<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kos;
use App\Models\Booking;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class SipkosController extends Controller
{
    public function index(Request $request)
    {
        $query = Kos::query();

        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->keyword . '%')
                    ->orWhere('lokasi', 'like', '%' . $request->keyword . '%')
                    ->orWhere('alamat', 'like', '%' . $request->keyword . '%');
            });
        }

        if ($request->filled('tipe_kos')) {
            $query->where('tipe_kos', $request->tipe_kos);
        }

        if ($request->filled('harga_maksimal')) {
            $query->where('harga', '<=', $request->harga_maksimal);
        }

        if ($request->has('ac')) {
            $query->where('ac', 1);
        }

        if ($request->has('wifi')) {
            $query->where('wifi', 1);
        }

        if ($request->has('kamar_mandi_dalam')) {
            $query->where('kamar_mandi_dalam', 1);
        }

        $semuaKos = $query->latest()->get();

        return view('home', compact('semuaKos'));
    }

    public function show($id)
    {
        $kos = Kos::findOrFail($id);

        $rekomendasi = Kos::where('lokasi', $kos->lokasi)
            ->where('id', '!=', $kos->id)
            ->take(2)
            ->get();

        return view('detail', compact('kos', 'rekomendasi'));
    }

    public function bookingForm($id)
    {
        $kos = Kos::findOrFail($id);

        return view('booking_form', compact('kos'));
    }

    public function storeBooking(Request $request, $id)
    {
        $request->validate([
            'tanggal_checkin' => 'required|date',
            'durasi' => 'required|integer|min:1',
            'jasa_pindahan' => 'nullable|in:0,1',
            'nama_jasa_pindahan' => 'nullable|string|max:100',
            'metode_pembayaran' => 'required|in:qris,dana,transfer_bank',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'tanggal_checkin.required' => 'Tanggal check-in wajib diisi.',
            'durasi.required' => 'Durasi sewa wajib diisi.',
            'durasi.integer' => 'Durasi harus berupa angka.',
            'durasi.min' => 'Durasi minimal 1 bulan.',
            'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih.',
            'bukti_pembayaran.required' => 'Bukti pembayaran wajib diupload.',
            'bukti_pembayaran.image' => 'Bukti pembayaran harus berupa gambar.',
            'bukti_pembayaran.mimes' => 'Format bukti pembayaran harus jpeg, png, jpg, atau webp.',
            'bukti_pembayaran.max' => 'Ukuran bukti pembayaran maksimal 2MB.',
        ]);

        $kos = Kos::findOrFail($id);

        $durasi = (int) $request->input('durasi', 1);
        $jasaPindahan = (int) $request->input('jasa_pindahan', 0);

        if ($jasaPindahan === 1 && !$request->filled('nama_jasa_pindahan')) {
            return response()->json([
                'message' => 'Silakan pilih jasa pindahan terlebih dahulu.',
                'errors' => [
                    'nama_jasa_pindahan' => ['Silakan pilih jasa pindahan terlebih dahulu.']
                ]
            ], 422);
        }

        $biayaJasaPindahan = $jasaPindahan === 1 ? 100000 : 0;
        $totalHarga = ($kos->harga * $durasi) + $biayaJasaPindahan;

        $folderBukti = public_path('bukti-pembayaran');

        if (!File::exists($folderBukti)) {
            File::makeDirectory($folderBukti, 0755, true, true);
        }

        $namaBukti = null;

        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $namaBukti = time() . '_' . uniqid() . '_bukti.' . $file->getClientOriginalExtension();
            $file->move($folderBukti, $namaBukti);
        }

        $kodeBooking = 'SIP-' . date('Ymd') . '-' . strtoupper(Str::random(5));

        Booking::create([
            'kode_booking' => $kodeBooking,
            'kos_id' => $kos->id,
            'tanggal_checkin' => $request->tanggal_checkin,
            'durasi' => $durasi,
            'jasa_pindahan' => $jasaPindahan,
            'nama_jasa_pindahan' => $jasaPindahan ? $request->input('nama_jasa_pindahan') : null,
            'total_harga' => $totalHarga,
            'metode_pembayaran' => $request->metode_pembayaran,
            'status_pembayaran' => 'Menunggu Verifikasi Admin',
            'bukti_pembayaran' => $namaBukti,
            'status' => 'pending',
        ]);

        session([
            'last_booking' => [
                'kode_booking' => $kodeBooking,
                'nama_kos' => $kos->nama,
                'lokasi' => $kos->lokasi,
                'alamat' => $kos->alamat,
                'tipe_kamar' => $kos->tipe_kos,
                'tanggal_checkin' => $request->tanggal_checkin,
                'durasi' => $durasi,
                'jasa_pindahan' => $jasaPindahan,
                'nama_jasa_pindahan' => $jasaPindahan ? $request->input('nama_jasa_pindahan') : null,
                'biaya_jasa_pindahan' => $biayaJasaPindahan,
                'total_harga' => $totalHarga,
                'metode_pembayaran' => $request->metode_pembayaran,
                'status_pembayaran' => 'Menunggu Verifikasi Admin',
                'bukti_pembayaran' => $namaBukti,
            ],
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Booking berhasil dibuat.',
            'kode_booking' => $kodeBooking,
            'no_wa' => $kos->no_telepon,
            'metode_pembayaran' => $request->metode_pembayaran,
            'status_pembayaran' => 'Menunggu Verifikasi Admin',
            'total_harga' => $totalHarga,
            'pdf_url' => route('booking.pdf'),
            'my_booking_url' => route('my.bookings'),
        ]);
    }

    public function myBookings()
    {
        $booking = session('last_booking');

        return view('my_bookings', compact('booking'));
    }

    public function konfirmasi()
    {
        return view('konfirmasi');
    }

    public function admin()
    {
        $semuaKos = Kos::latest()->get();

        $semuaBooking = Booking::with('kos')
            ->latest()
            ->get();

        return view('admin', compact('semuaKos', 'semuaBooking'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'alamat' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'tipe_kos' => 'required|string|in:Pria,Wanita,Campur',
            'deskripsi' => 'required|string',
            'no_telepon' => 'required|string|max:30',
            'foto_utama' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'foto_2' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'foto_3' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $targetPath = public_path('foto-kos');

        if (!File::exists($targetPath)) {
            File::makeDirectory($targetPath, 0755, true, true);
        }

        $namaFotoUtama = $this->uploadFoto($request, 'foto_utama', $targetPath, 'utama');
        $namaFoto2 = $this->uploadFoto($request, 'foto_2', $targetPath, 'pendukung_1');
        $namaFoto3 = $this->uploadFoto($request, 'foto_3', $targetPath, 'pendukung_2');

        Kos::create([
            'nama' => $request->nama,
            'lokasi' => $request->lokasi,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'alamat' => $request->alamat,
            'harga' => $request->harga,
            'tipe_kos' => $request->tipe_kos,
            'ac' => $request->has('ac') ? 1 : 0,
            'wifi' => $request->has('wifi') ? 1 : 0,
            'kamar_mandi_dalam' => $request->has('kamar_mandi_dalam') ? 1 : 0,
            'deskripsi' => $request->deskripsi,
            'no_telepon' => $request->no_telepon,
            'foto' => $namaFotoUtama,
            'foto_2' => $namaFoto2,
            'foto_3' => $namaFoto3,
        ]);

        return redirect()
            ->route('admin')
            ->with('sukses', 'Kos berhasil ditambahkan dan titik maps sudah tersimpan!');
    }

    public function edit($id)
    {
        $kos = Kos::findOrFail($id);

        return view('admin_edit', compact('kos'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'alamat' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'tipe_kos' => 'required|string|in:Pria,Wanita,Campur',
            'deskripsi' => 'required|string',
            'no_telepon' => 'required|string|max:30',
            'foto_utama' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'foto_2' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'foto_3' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $kos = Kos::findOrFail($id);

        $targetPath = public_path('foto-kos');

        if (!File::exists($targetPath)) {
            File::makeDirectory($targetPath, 0755, true, true);
        }

        $namaFotoUtama = $kos->foto;
        $namaFoto2 = $kos->foto_2;
        $namaFoto3 = $kos->foto_3;

        if ($request->hasFile('foto_utama')) {
            $this->hapusFoto($kos->foto);
            $namaFotoUtama = $this->uploadFoto($request, 'foto_utama', $targetPath, 'utama');
        }

        if ($request->hasFile('foto_2')) {
            $this->hapusFoto($kos->foto_2);
            $namaFoto2 = $this->uploadFoto($request, 'foto_2', $targetPath, 'pendukung_1');
        }

        if ($request->hasFile('foto_3')) {
            $this->hapusFoto($kos->foto_3);
            $namaFoto3 = $this->uploadFoto($request, 'foto_3', $targetPath, 'pendukung_2');
        }

        $kos->update([
            'nama' => $request->nama,
            'lokasi' => $request->lokasi,
            'latitude' => $request->filled('latitude') ? $request->latitude : $kos->latitude,
            'longitude' => $request->filled('longitude') ? $request->longitude : $kos->longitude,
            'alamat' => $request->alamat,
            'harga' => $request->harga,
            'tipe_kos' => $request->tipe_kos,
            'ac' => $request->has('ac') ? 1 : 0,
            'wifi' => $request->has('wifi') ? 1 : 0,
            'kamar_mandi_dalam' => $request->has('kamar_mandi_dalam') ? 1 : 0,
            'deskripsi' => $request->deskripsi,
            'no_telepon' => $request->no_telepon,
            'foto' => $namaFotoUtama,
            'foto_2' => $namaFoto2,
            'foto_3' => $namaFoto3,
        ]);

        return redirect()
            ->route('admin')
            ->with('sukses', 'Data kos berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kos = Kos::findOrFail($id);

        $this->hapusFoto($kos->foto);
        $this->hapusFoto($kos->foto_2);
        $this->hapusFoto($kos->foto_3);

        $kos->delete();

        return redirect()
            ->route('admin')
            ->with('sukses', 'Data kos berhasil dihapus!');
    }

    public function downloadBookingPdf()
    {
        $booking = session('last_booking');

        if (!$booking) {
            return redirect()
                ->route('my.bookings')
                ->with('error', 'Tidak ada data booking untuk dicetak.');
        }

        $kodeBooking = $booking['kode_booking'] ?? 'booking-kos';
        $namaFile = 'bukti-booking-' . Str::slug($kodeBooking) . '.pdf';

        $pdf = Pdf::loadView('booking_pdf', compact('booking'))
            ->setPaper('a4', 'portrait');

        return $pdf->download($namaFile);
    }

    private function uploadFoto(Request $request, string $field, string $targetPath, string $label): ?string
    {
        if (!$request->hasFile($field)) {
            return null;
        }

        $file = $request->file($field);
        $namaFile = time() . '_' . uniqid() . '_' . $label . '.' . $file->getClientOriginalExtension();

        $file->move($targetPath, $namaFile);

        return $namaFile;
    }

    private function hapusFoto(?string $namaFile): void
    {
        if (!$namaFile) {
            return;
        }

        $path = public_path('foto-kos/' . $namaFile);

        if (File::exists($path)) {
            File::delete($path);
        }
    }
}