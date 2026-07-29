<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SURAT PERJANJIAN SEWA MENYEWA PROPERTI</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 40px;
            color: #000;
            line-height: 1.6;
            font-size: 14px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 18px;
            text-decoration: underline;
            margin: 0;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 12px;
        }
        .section-title {
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 5px;
            text-decoration: underline;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px 12px;
            text-align: left;
        }
        .text-center {
            text-align: center;
        }
        .signatures {
            margin-top: 50px;
            width: 100%;
        }
        .signatures td {
            border: none;
            width: 50%;
            text-align: center;
            padding-top: 80px;
        }
        .no-border {
            border: none;
        }
        .no-border td {
            border: none;
            padding: 4px 0;
        }
        .print-btn-container {
            margin-bottom: 20px;
            text-align: right;
        }
        .print-btn {
            background-color: #4f46e5;
            color: white;
            border: none;
            padding: 8px 16px;
            font-size: 12px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }
        @media print {
            .print-btn-container {
                display: none;
            }
            body {
                margin: 20px;
            }
        }
    </style>
</head>
<body>

    <div class="print-btn-container">
        <button class="print-btn" onclick="window.print()">Cetak / Simpan PDF</button>
    </div>

    <div class="header">
        <h1>SURAT PERJANJIAN SEWA MENYEWA PROPERTI</h1>
        <p>Nomor: {{ $booking->contract->contract_number }}</p>
    </div>

    <p>Pada hari ini, <strong>{{ $booking->contract->created_at->translatedFormat('l, d F Y') }}</strong>, kami yang bertanda tangan di bawah ini:</p>

    <div class="section-title">PIHAK PERTAMA (Pemilik/Yang Menyewakan)</div>
    <table class="no-border">
        <tr>
            <td style="width: 25%;">Nama Lengkap</td>
            <td style="width: 5%;">:</td>
            <td>{{ $booking->property->user->name }}</td>
        </tr>
        <tr>
            <td>Nomor HP</td>
            <td>:</td>
            <td>{{ $booking->property->user->phone }}</td>
        </tr>
        <tr>
            <td>Peran Dalam Perjanjian</td>
            <td>:</td>
            <td>Pemilik sah properti yang dipasarkan.</td>
        </tr>
    </table>

    <div class="section-title">PIHAK KEDUA (Penyewa)</div>
    <table class="no-border">
        <tr>
            <td style="width: 25%;">Nama Lengkap</td>
            <td style="width: 5%;">:</td>
            <td>{{ $booking->tenant->name }}</td>
        </tr>
        <tr>
            <td>Nomor HP</td>
            <td>:</td>
            <td>{{ $booking->tenant->phone }}</td>
        </tr>
        <tr>
            <td>Peran Dalam Perjanjian</td>
            <td>:</td>
            <td>Pihak yang mengajukan sewa atas objek properti.</td>
        </tr>
    </table>

    <p>Kedua belah pihak dengan ini sepakat untuk melakukan Perjanjian Sewa Menyewa atas aset properti berikut:</p>

    <div class="section-title">OBJEK SEWA</div>
    <table class="no-border">
        <tr>
            <td style="width: 25%;">Nama Properti</td>
            <td style="width: 5%;">:</td>
            <td><strong>{{ $booking->property->title }}</strong></td>
        </tr>
        <tr>
            <td>Kategori Properti</td>
            <td>:</td>
            <td>{{ $booking->property->category->name }}</td>
        </tr>
        <tr>
            <td>Lokasi Wilayah</td>
            <td>:</td>
            <td>{{ $booking->property->location->name }}</td>
        </tr>
        <tr>
            <td>Spesifikasi Teknis</td>
            <td>:</td>
            <td>LB: {{ (int)$booking->property->building_area }} m² | LT: {{ (int)$booking->property->land_area }} m² | KT: {{ $booking->property->bedrooms ?? 0 }} | KM: {{ $booking->property->bathrooms ?? 0 }}</td>
        </tr>
    </table>

    <div class="section-title">PASAL 1 - DURASI SEWA & PERIODE</div>
    <ol>
        <li>Sewa menyewa ini dilangsungkan untuk jangka waktu tertentu, dimulai dari tanggal <strong>{{ $booking->start_date->format('d F Y') }}</strong> sampai dengan tanggal <strong>{{ $booking->end_date->format('d F Y') }}</strong>.</li>
        <li>Satuan sewa yang dipilih adalah sewa berkala: <strong>{{ ucfirst($booking->duration_type) }}</strong>.</li>
    </ol>

    <div class="section-title">PASAL 2 - BIAYA SEWA & DEPOSIT</div>
    <ol>
        <li>Total biaya sewa properti untuk periode tersebut adalah sebesar <strong>Rp {{ number_format($booking->total_price, 2, ',', '.') }}</strong>.</li>
        <li>PIHAK KEDUA telah menyerahkan uang jaminan (deposit) sebesar <strong>Rp {{ number_format($booking->deposit, 2, ',', '.') }}</strong> kepada sistem platform sebagai jaminan pemeliharaan properti.</li>
        <li>Uang jaminan (deposit) akan dikembalikan sepenuhnya oleh PIHAK PERTAMA kepada PIHAK KEDUA dalam jangka waktu maksimal 7 hari setelah masa sewa berakhir, dengan catatan objek sewa dikembalikan dalam keadaan baik dan tidak ada kerusakan fatal atau tunggakan tagihan fasilitas (listrik/air).</li>
    </ol>

    <div class="section-title">PASAL 3 - TANGGUNG JAWAB PEMELIHARAAN</div>
    <ol>
        <li>PIHAK KEDUA bertanggung jawab penuh untuk merawat objek sewa, menjaga kebersihan, serta membayar tagihan pemakaian bulanan (seperti listrik, air, iuran lingkungan) selama masa sewa berlangsung.</li>
        <li>Kerusakan struktur utama bangunan (seperti atap bocor parah, keretakan dinding utama) menjadi tanggung jawab penuh PIHAK PERTAMA, kecuali kerusakan disebabkan kelalaian PIHAK KEDUA.</li>
    </ol>

    <p>Demikian Surat Perjanjian Sewa Menyewa ini dibuat secara sadar, tanpa paksaan, dan disepakati kedua belah pihak untuk dipatuhi bersama.</p>

    <table class="signatures">
        <tr>
            <td>
                PIHAK PERTAMA (Pemilik)<br><br><br><br>
                ( <strong>{{ $booking->property->user->name }}</strong> )
            </td>
            <td>
                PIHAK KEDUA (Penyewa)<br><br><br><br>
                ( <strong>{{ $booking->tenant->name }}</strong> )
            </td>
        </tr>
    </table>

</body>
</html>
