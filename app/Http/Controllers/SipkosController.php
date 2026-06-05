<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kos;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf;

class SipkosController extends Controller
{
    // 1. Tampilan Utama Pencarian Kos
    public function index(Request $request)
    {
        $query = Kos::query();

        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->keyword . '%')
                    ->orWhere('lokasi', 'like', '%' . $request->keyword . '%');
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

        $semuaKos = $query->get();
        return view('home', compact('semuaKos'));
    }

    // 2. Tampilan Detail Kos
    public function show($id)
    {
        $kos = Kos::findOrFail($id);

        $rekomendasi = Kos::where('lokasi', $kos->lokasi)
            ->where('id', '!=', $kos->id)
            ->take(2)
            ->get();

        return view('detail', compact('kos', 'rekomendasi'));
    }

    // 3. Tampilan Formulir Booking Kos
    public function bookingForm($id)
    {
        $kos = Kos::findOrFail($id);
        return view('booking_form', compact('kos'));
    }

    // 4. Mengubah nama fungsi agar sinkron dengan rute post transaksi booking
    public function storeBooking(Request $request, $id)
    {
        $kos = Kos::findOrFail($id);
        $totalHarga = $request->input('total_harga_input', $kos->harga);

        session([
            'last_booking' => [
                'nama_kos' => $kos->nama,
                'lokasi' => $kos->lokasi,
                'alamat' => $kos->alamat,
                'tipe_kamar' => $kos->tipe_kos,
                'tanggal_checkin' => $request->tanggal_checkin,
                'durasi' => $request->input('durasi', 1),
                'jasa_pindahan' => $request->input('jasa_pindahan', 0),
                'total_harga' => $totalHarga,
            ]
        ]);

        return response()->json([
            'status' => 'success',
            'no_wa' => $kos->no_telepon
        ]);
    }

    // 5. Tampilan Riwayat / Ringkasan Booking
    public function myBookings()
    {
        $booking = session('last_booking');
        return view('my_bookings', compact('booking'));
    }

    // 6. Tampilan Konfirmasi Kontak Pemilik Kos
    public function konfirmasi()
    {
        return view('konfirmasi');
    }

    // 7. Tampilan Dashboard Admin
    public function admin()
    {
        $semuaKos = Kos::all();
        return view('admin', compact('semuaKos'));
    }

    // 8. Fungsi Simpan Kos Baru dari Form Admin
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'lokasi' => 'required',
            'alamat' => 'required',
            'harga' => 'required|numeric',
            'tipe_kos' => 'required',
            'deskripsi' => 'required',
            'no_telepon' => 'required',
            // Disamakan menggunakan 'foto_utama' sesuai nama atribut name di file admin.blade.php
            'foto_utama' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'foto_2' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'foto_3' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // 1. Tentukan jalur path folder tujuan & buat jika belum ada
        $targetPath = public_path('foto-kos');
        if (!File::exists($targetPath)) {
            File::makeDirectory($targetPath, 0755, true, true);
        }

        // 2. Siapkan variabel penampung nama file foto (default null)
        $namaFotoUtama = null;
        $namaFoto2 = null;
        $namaFoto3 = null;

        // 3. Proses upload Foto Utama jika ada
        if ($request->hasFile('foto_utama')) {
            $file = $request->file('foto_utama');
            $namaFotoUtama = time() . '_utama.' . $file->getClientOriginalExtension();
            $file->move($targetPath, $namaFotoUtama);
        }

        // 4. Proses upload Foto Pendukung 1 jika ada
        if ($request->hasFile('foto_2')) {
            $file = $request->file('foto_2');
            $namaFoto2 = time() . '_2.' . $file->getClientOriginalExtension();
            $file->move($targetPath, $namaFoto2);
        }

        // 5. Proses upload Foto Pendukung 2 jika ada
        if ($request->hasFile('foto_3')) {
            $file = $request->file('foto_3');
            $namaFoto3 = time() . '_3.' . $file->getClientOriginalExtension();
            $file->move($targetPath, $namaFoto3);
        }

        // 6. Simpan seluruh data ke database
        Kos::create([
            'nama' => $request->nama,
            'lokasi' => $request->lokasi,
            'alamat' => $request->alamat,
            'harga' => $request->harga,
            'tipe_kos' => $request->tipe_kos,
            'ac' => $request->has('ac') ? 1 : 0,
            'wifi' => $request->has('wifi') ? 1 : 0,
            'kamar_mandi_dalam' => $request->has('kamar_mandi_dalam') ? 1 : 0,
            'deskripsi' => $request->deskripsi,
            'no_telepon' => $request->no_telepon,
            'foto' => $namaFotoUtama, // Tetap masuk ke kolom 'foto' lama di database
            'foto_2' => $namaFoto2,   // Masuk ke kolom baru 'foto_2'
            'foto_3' => $namaFoto3,   // Masuk ke kolom baru 'foto_3'
        ]);

        return redirect()->route('admin')->with('sukses', 'Kos Berhasil Ditambahkan!');
    }

    // 9. Fungsi Tampil Form Edit Kos
    public function edit($id)
    {
        $kos = Kos::findOrFail($id);
        return view('admin_edit', compact('kos'));
    }

    // 10. Fungsi Memperbarui Data Kos
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'lokasi' => 'required',
            'alamat' => 'required',
            'harga' => 'required|numeric',
            'tipe_kos' => 'required',
            'deskripsi' => 'required',
            'no_telepon' => 'required',
            // Validasi disesuaikan dengan nama input di admin_edit.blade.php
            'foto_utama' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'foto_2' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'foto_3' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $kos = Kos::findOrFail($id);

        // Default pakai nama foto yang sudah ada di database saat ini
        $namaFotoUtama = $kos->foto;
        $namaFoto2 = $kos->foto_2;
        $namaFoto3 = $kos->foto_3;

        $targetPath = public_path('foto-kos');

        // Pastikan folder tujuan ada di public/foto-kos
        if (!File::exists($targetPath)) {
            File::makeDirectory($targetPath, 0755, true, true);
        }

        // 1. PROSES UPDATE FOTO UTAMA
        if ($request->hasFile('foto_utama')) {
            // Hapus foto utama lama jika ada di folder
            if ($kos->foto && File::exists($targetPath . '/' . $kos->foto)) {
                File::delete($targetPath . '/' . $kos->foto);
            }

            $file = $request->file('foto_utama');
            $namaFotoUtama = time() . '_utama.' . $file->getClientOriginalExtension();
            $file->move($targetPath, $namaFotoUtama);
        }

        // 2. PROSES UPDATE FOTO PENDUKUNG 1
        if ($request->hasFile('foto_2')) {
            // Hapus foto pendukung 1 lama jika ada di folder
            if ($kos->foto_2 && File::exists($targetPath . '/' . $kos->foto_2)) {
                File::delete($targetPath . '/' . $kos->foto_2);
            }

            $file = $request->file('foto_2');
            $namaFoto2 = time() . '_2.' . $file->getClientOriginalExtension();
            $file->move($targetPath, $namaFoto2);
        }

        // 3. PROSES UPDATE FOTO PENDUKUNG 2
        if ($request->hasFile('foto_3')) {
            // Hapus foto pendukung 2 lama jika ada di folder
            if ($kos->foto_3 && File::exists($targetPath . '/' . $kos->foto_3)) {
                File::delete($targetPath . '/' . $kos->foto_3);
            }

            $file = $request->file('foto_3');
            $namaFoto3 = time() . '_3.' . $file->getClientOriginalExtension();
            $file->move($targetPath, $namaFoto3);
        }

        // Eksekusi update data ke database
        $kos->update([
            'nama' => $request->nama,
            'lokasi' => $request->lokasi,
            'alamat' => $request->alamat,
            'harga' => $request->harga,
            'tipe_kos' => $request->tipe_kos,
            'ac' => $request->has('ac') ? 1 : 0,
            'wifi' => $request->has('wifi') ? 1 : 0,
            'kamar_mandi_dalam' => $request->has('kamar_mandi_dalam') ? 1 : 0,
            'deskripsi' => $request->deskripsi,
            'no_telepon' => $request->no_telepon,
            'foto' => $namaFotoUtama, // Kolom foto utama
            'foto_2' => $namaFoto2,   // Kolom foto pendukung 1
            'foto_3' => $namaFoto3,   // Kolom foto pendukung 2
        ]);

        return redirect()->route('admin')->with('sukses', 'Data Kos Berhasil Diperbarui!');
    }
    // 11. Fungsi Hapus Kos
    public function destroy($id)
    {
        $kos = Kos::findOrFail($id);

        // Hapus foto fisik saat data kos dihapus
        if ($kos->foto && file_exists(public_path('foto-kos/' . $kos->foto))) {
            unlink(public_path('foto-kos/' . $kos->foto));
        }

        $kos->delete();
        return redirect()->route('admin')->with('sukses', 'Data Kos Berhasil Dihapus!');
    }

    public function downloadBookingPdf()
    {
        $booking = session('last_booking');

        if (!$booking) {
            return redirect()->route('my.bookings')
                ->with('error', 'Tidak ada data booking.');
        }

        $pdf = Pdf::loadView('booking_pdf', compact('booking'));

        return $pdf->download('booking-kos.pdf');
    }
}