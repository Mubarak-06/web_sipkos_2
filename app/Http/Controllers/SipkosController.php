<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kos; // Memanggil model Kos yang kita buat tadi

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

    // 4. PERBAIKAN: Mengubah nama fungsi agar sinkron dengan rute post transaksi booking
    public function storeBooking(Request $request, $id)
    {
        $kos = Kos::findOrFail($id);
        $totalHarga = $request->input('total_harga_input', $kos->harga);

        session([
            'last_booking' => [
                'nama_kos' => $kos->nama,
                'lokasi' => $kos->lokasi,    // Tetap simpan lokasi
                'alamat' => $kos->alamat,    // <-- GANTI INI: ambil dari kolom alamat baru
                'tipe_kamar' => $kos->tipe_kos,
                'tanggal_checkin' => $request->tanggal_checkin,
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
        ]);

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
        ]);

        $kos = Kos::findOrFail($id);
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
        ]);

        return redirect()->route('admin')->with('sukses', 'Data Kos Berhasil Diperbarui!');
    }

    // 11. Fungsi Hapus Kos
    public function destroy($id)
    {
        $kos = Kos::findOrFail($id);
        $kos->delete();

        return redirect()->route('admin')->with('sukses', 'Data Kos Berhasil Dihapus!');
    }
}