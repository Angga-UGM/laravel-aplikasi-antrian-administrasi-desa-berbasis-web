<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Nomor Antrian - Kantor Desa Selokromo</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('logo-desa-selokromo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('logo-desa-selokromo.png') }}">

    <style>
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none;
            }
            .card {
                border: 1px solid #ddd;
                page-break-inside: avoid;
            }
        }
        body {
            font-family: 'Courier New', monospace;
            background: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .card {
            background: white;
            width: 350px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            text-align: center;
        }
        .header {
            border-bottom: 2px dashed #2d6a4f;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h2 {
            color: #2d6a4f;
            margin: 0;
        }
        .header h3 {
            color: #40916c;
            margin: 5px 0 0;
        }
        .nomor {
            font-size: 48px;
            font-weight: bold;
            color: #2d6a4f;
            margin: 20px 0;
            letter-spacing: 2px;
        }
        .info {
            text-align: left;
            margin: 15px 0;
            font-size: 12px;
        }
        .info p {
            margin: 5px 0;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #666;
        }
        button {
            background: #2d6a4f;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            margin-right: 10px;
        }
        button:hover {
            background: #1b4332;
        }
    </style>
</head>
<body>
    <div>
        <div class="card">
            <div class="header">
                <h2>KANTOR DESA SELOKROMO</h2>
                <h3>Sistem Antrian Online</h3>
            </div>

            <p>Nomor Antrian Anda:</p>
            <div class="nomor">{{ $antrian->nomor_antrian }}</div>

            <div class="info">
                <p><strong>Nama:</strong> {{ $antrian->nama }}</p>
                <p><strong>Keperluan:</strong> {{ $antrian->keperluan }}</p>
                <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($antrian->tanggal_antrian)->format('d/m/Y') }}</p>
                <p><strong>Waktu Daftar:</strong> {{ \Carbon\Carbon::parse($antrian->waktu_daftar)->format('H:i:s') }}</p>
                <p><strong>Status:</strong> {{ ucfirst($antrian->status) }}</p>
            </div>

            <div class="footer">
                <p>Harap simpan nomor antrian ini</p>
                <p>Perhatikan layar antrian untuk panggilan</p>
                <p>Terima kasih</p>
            </div>
        </div>

        <div class="no-print" style="text-align: center; margin-top: 20px;">
            <button onclick="window.print()">
                <i class="fas fa-print"></i> Cetak / Simpan PDF
            </button>
            <button onclick="window.location.href='{{ route('antrian.form') }}'">
                <i class="fas fa-home"></i> Kembali
            </button>
        </div>
    </div>

    <script>
        console.log('Halaman siap, silakan klik tombol cetak jika ingin mencetak');
    </script>
</body>
</html>
