<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Bukti Booking SIPKOS</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #334155;
            font-size: 12px;
            margin: 30px;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .logo {
            width: 110px;
            margin-bottom: 10px;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 5px;
        }

        .subtitle {
            color: #64748b;
            font-size: 11px;
        }

        .card {
            border: 1px solid #dbeafe;
            border-radius: 10px;
            padding: 15px;
            background-color: #f8fbff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #2563eb;
            color: white;
            text-align: left;
            padding: 10px;
            width: 35%;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
        }

        .total-box {
            margin-top: 20px;
            text-align: center;
            background: #eff6ff;
            border: 2px solid #2563eb;
            border-radius: 10px;
            padding: 15px;
        }

        .total-label {
            color: #64748b;
            font-size: 12px;
        }

        .total-price {
            color: #2563eb;
            font-size: 24px;
            font-weight: bold;
        }

        .footer {
            margin-top: 35px;
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
        }

        .status {
            display: inline-block;
            padding: 4px 10px;
            background: #dbeafe;
            color: #2563eb;
            font-weight: bold;
            border-radius: 20px;
        }
    </style>
</head>

<body>

    <div class="header">
        <img src="{{ public_path('logo-sipkos.jpeg') }}" class="logo">

        <div class="title">
            SIPKOS
        </div>

        <div class="subtitle">
            Sistem Informasi Pencarian dan Booking Kos
        </div>
    </div>

    <div class="card">
        <table>
            <tr>
                <th>Nama Kos</th>
                <td>{{ $booking['nama_kos'] }}</td>
            </tr>

            <tr>
                <th>Alamat</th>
                <td>{{ $booking['alamat'] }}</td>
            </tr>

            <tr>
                <th>Tipe Kos</th>
                <td>{{ $booking['tipe_kamar'] }}</td>
            </tr>

            <tr>
                <th>Tanggal Check In</th>
                <td>{{ $booking['tanggal_checkin'] }}</td>
            </tr>

            <tr>
                <th>Durasi Sewa</th>
                <td>{{ $booking['durasi'] }} Bulan</td>
            </tr>

            <tr>
                <th>Jasa Pindahan</th>
                <td>
                    {{ $booking['jasa_pindahan'] ? 'Ya' : 'Tidak' }}
                </td>
            </tr>

            <tr>
                <th>Status</th>
                <td>
                    <span class="status">
                        Menunggu Konfirmasi Pemilik
                    </span>
                </td>
            </tr>
        </table>

        <div class="total-box">
            <div class="total-label">
                TOTAL PEMBAYARAN
            </div>

            <div class="total-price">
                Rp {{ number_format($booking['total_harga'], 0, ',', '.') }}
            </div>
        </div>
    </div>

    <div class="footer">
        Dokumen ini dibuat secara otomatis oleh Sistem SIPKOS.<br>
        Simpan dokumen ini sebagai bukti booking kos Anda.
    </div>

</body>

</html>