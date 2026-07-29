<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>KUITANSI PEMBAYARAN DIGITAL</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            color: #333;
            line-height: 1.5;
            font-size: 14px;
        }
        .invoice-card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 30px;
            background: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            max-width: 600px;
            margin: 0 auto;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }
        .logo {
            font-size: 20px;
            font-weight: bold;
            color: #4f46e5;
        }
        .title {
            font-size: 16px;
            font-weight: bold;
            color: #0f172a;
            text-align: right;
        }
        .details-grid {
            display: grid;
            grid-cols: 1;
            gap: 15px;
            margin-bottom: 25px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f8fafc;
        }
        .detail-row span:first-child {
            color: #64748b;
            font-weight: 500;
        }
        .detail-row span:last-child {
            color: #0f172a;
            font-weight: bold;
        }
        .total-box {
            background-color: #f8fafc;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            font-size: 18px;
            font-weight: 800;
            color: #4f46e5;
            border: 1px dashed #cbd5e1;
            margin-bottom: 30px;
        }
        .footer {
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
            padding-top: 15px;
        }
        .print-btn-container {
            margin-bottom: 20px;
            text-align: right;
            max-width: 600px;
            margin: 0 auto 20px auto;
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
        .status-badge {
            background-color: #d1fae5;
            color: #065f46;
            padding: 4px 8px;
            border-radius: 9999px;
            font-size: 11px;
            text-transform: uppercase;
        }
        @page {
            size: auto;
            margin: 10mm;
        }
        @media print {
            * {
                background: transparent !important;
                color: #000 !important;
                box-shadow: none !important;
            }
            .print-btn-container {
                display: none;
            }
            body {
                background: white !important;
                color: black !important;
                margin: 0;
                padding: 10px;
                font-size: 12px;
            }
            .invoice-card {
                border: none !important;
                box-shadow: none !important;
                padding: 10px;
                width: 95% !important;
                max-width: 95% !important;
                margin: 0 auto !important;
                background: white !important;
                box-sizing: border-box !important;
            }
            .header {
                padding-bottom: 8px;
                margin-bottom: 12px;
            }
            .details-grid {
                gap: 8px;
                margin-bottom: 15px;
            }
            .detail-row {
                padding: 4px 0;
            }
            .total-box {
                padding: 10px;
                margin-bottom: 15px;
                font-size: 14px;
                border: 1px dashed #000 !important;
            }
            .footer {
                padding-top: 10px;
                font-size: 9px;
            }
        }
    </style>
</head>
<body class="bg-slate-50" onload="window.print()">

    <div class="print-btn-container">
        <button class="print-btn" onclick="window.print()">Cetak Kuitansi</button>
    </div>

    <div class="invoice-card">
        <div class="header">
            <span class="logo">PropertiImpian</span>
            <div class="title">
                KUITANSI PEMBAYARAN
                <p style="font-size:10px; color:#94a3b8; font-weight:normal; margin: 3px 0 0 0;">Ref: {{ $payment->gateway_reference }}</p>
            </div>
        </div>

        <div class="details-grid">
            <div class="detail-row">
                <span>Tanggal Bayar</span>
                <span>{{ $payment->paid_at ? $payment->paid_at->format('d M Y H:i') : '-' }} WIB</span>
            </div>
            
            <div class="detail-row">
                <span>Nama Pengirim</span>
                <span>
                    @if(class_basename($payment->payable_type) === 'Booking')
                        {{ $payment->payable->tenant->name }}
                    @else
                        {{ $payment->payable->buyer->name }}
                    @endif
                </span>
            </div>

            <div class="detail-row">
                <span>Objek Properti</span>
                <span>{{ $payment->payable->property->title }}</span>
            </div>

            <div class="detail-row">
                <span>Tipe Transaksi</span>
                <span>{{ class_basename($payment->payable_type) === 'Booking' ? 'Sewa Properti' : 'Pembelian Properti' }}</span>
            </div>

            <div class="detail-row">
                <span>Metode Pembayaran</span>
                <span>{{ $payment->method }}</span>
            </div>

            <div class="detail-row">
                <span>Status Kuitansi</span>
                <span>
                    <span class="status-badge">{{ $payment->status }}</span>
                </span>
            </div>
        </div>

        <div class="total-box">
            TOTAL DIBAYAR: Rp {{ number_format($payment->amount, 2, ',', '.') }}
        </div>

        <div class="footer">
            Kuitansi ini adalah bukti pembayaran elektronik sah yang diterbitkan otomatis oleh sistem PropertiImpian.<br>
            Jika Anda butuh bantuan, hubungi cs@propertiimpian.com
        </div>
    </div>

</body>
</html>
