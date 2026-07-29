<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SURAT PERJANJIAN PENGIKATAN JUAL BELI (PPJB)</title>
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
        <h1>SURAT PERJANJIAN PENGIKATAN JUAL BELI (PPJB)</h1>
        <p>Nomor: {{ $transaction->contract->contract_number }}</p>
    </div>

    <p>Pada hari ini, <strong>{{ $transaction->contract->created_at->translatedFormat('l, d F Y') }}</strong>, kami yang bertanda tangan di bawah ini:</p>

    <div class="section-title">PIHAK PERTAMA (Penjual)</div>
    <table class="no-border">
        <tr>
            <td style="width: 25%;">Nama Lengkap</td>
            <td style="width: 5%;">:</td>
            <td>{{ $transaction->property->user->name }}</td>
        </tr>
        <tr>
            <td>Nomor HP</td>
            <td>:</td>
            <td>{{ $transaction->property->user->phone }}</td>
        </tr>
        <tr>
            <td>Peran Dalam Perjanjian</td>
            <td>:</td>
            <td>Pemilik sah properti yang dipasarkan.</td>
        </tr>
    </table>

    <div class="section-title">PIHAK KEDUA (Pembeli)</div>
    <table class="no-border">
        <tr>
            <td style="width: 25%;">Nama Lengkap</td>
            <td style="width: 5%;">:</td>
            <td>{{ $transaction->buyer->name }}</td>
        </tr>
        <tr>
            <td>Nomor HP</td>
            <td>:</td>
            <td>{{ $transaction->buyer->phone }}</td>
        </tr>
        <tr>
            <td>Peran Dalam Perjanjian</td>
            <td>:</td>
            <td>Pihak pembeli yang mengajukan pemesanan unit.</td>
        </tr>
    </table>

    <p>Kedua belah pihak dengan ini sepakat untuk mengikatkan diri dalam Perjanjian Jual Beli atas aset properti berikut:</p>

    <div class="section-title">OBJEK PROPERTI</div>
    <table class="no-border">
        <tr>
            <td style="width: 25%;">Nama Properti</td>
            <td style="width: 5%;">:</td>
            <td><strong>{{ $transaction->property->title }}</strong></td>
        </tr>
        <tr>
            <td>Kategori Properti</td>
            <td>:</td>
            <td>{{ $transaction->property->category->name }}</td>
        </tr>
        <tr>
            <td>Lokasi Wilayah</td>
            <td>:</td>
            <td>{{ $transaction->property->location->name }}</td>
        </tr>
        <tr>
            <td>Spesifikasi Teknis</td>
            <td>:</td>
            <td>LB: {{ (int)$transaction->property->building_area }} m² | LT: {{ (int)$transaction->property->land_area }} m² | KT: {{ $transaction->property->bedrooms ?? 0 }} | KM: {{ $transaction->property->bathrooms ?? 0 }}</td>
        </tr>
        <tr>
            <td>Sertifikat Kepemilikan</td>
            <td>:</td>
            <td>{{ $transaction->property->certificate_type ?? 'SHM / HGB' }}</td>
        </tr>
    </table>

    <div class="section-title">PASAL 1 - NILAI TRANSAKSI & PEMBAYARAN</div>
    <ol>
        <li>Harga kesepakatan jual-beli properti ini adalah sebesar <strong>Rp {{ number_format($transaction->agreed_price, 2, ',', '.') }}</strong>.</li>
        <li>PIHAK KEDUA telah melakukan pembayaran tanda jadi (*Booking Fee*) sebesar 1% dari nilai transaksi yaitu sejumlah <strong>Rp {{ number_format($transaction->booking_fee, 2, ',', '.') }}</strong> yang sah dan diterima oleh sistem platform.</li>
        <li>Sisa pelunasan sebesar <strong>Rp {{ number_format($transaction->agreed_price - $transaction->booking_fee, 2, ',', '.') }}</strong> wajib dilunasi oleh PIHAK KEDUA sesuai waktu yang disepakati bersama di luar sistem atau melalui kanal transfer perbankan yang ditunjuk.</li>
    </ol>

    <div class="section-title">PASAL 2 - JAMINAN DAN PENYERAHAN</div>
    <ol>
        <li>PIHAK PERTAMA menjamin penuh bahwa objek properti yang diperjualbelikan merupakan hak milik sah miliknya dan bebas dari tuntutan hukum atau sengketa pihak ketiga.</li>
        <li>Kunci dan penyerahan fisik objek properti dilakukan secara resmi setelah pelunasan diselesaikan sepenuhnya oleh PIHAK KEDUA dan dikonfirmasi oleh PIHAK PERTAMA.</li>
    </ol>

    <p>Demikian Surat Perjanjian Pengikatan Jual Beli ini dibuat dengan penuh kesadaran dan tanpa paksaan dari pihak mana pun untuk digunakan sebagaimana mestinya.</p>

    <table class="signatures">
        <tr>
            <td>
                PIHAK PERTAMA (Penjual)<br><br><br><br>
                ( <strong>{{ $transaction->property->user->name }}</strong> )
            </td>
            <td>
                PIHAK KEDUA (Pembeli)<br><br><br><br>
                ( <strong>{{ $transaction->buyer->name }}</strong> )
            </td>
        </tr>
    </table>

</body>
</html>
