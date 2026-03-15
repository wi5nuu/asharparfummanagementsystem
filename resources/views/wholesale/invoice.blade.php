<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Wholesale #{{ $order->invoice_number }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1a202c;
            --secondary-color: #34495e;
            --accent-color: #3498db;
            --text-color: #333;
            --border-color: #eee;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            font-size: 14px;
            color: var(--text-color);
            margin: 0;
            padding: 40px;
            background: #fff;
            line-height: 1.6;
        }

        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            border: 1px solid var(--border-color);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            padding: 30px;
            border-radius: 8px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 20px;
        }

        .logo-section h1 {
            color: var(--primary-color);
            margin: 0;
            font-size: 28px;
            letter-spacing: -1px;
            text-transform: uppercase;
        }

        .logo-section p {
            margin: 5px 0 0;
            font-size: 12px;
            color: #777;
        }

        .invoice-details {
            text-align: right;
        }

        .invoice-details h2 {
            margin: 0;
            color: var(--accent-color);
            font-size: 22px;
        }

        .invoice-details p {
            margin: 3px 0;
            font-size: 13px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .info-block h3 {
            font-size: 12px;
            text-transform: uppercase;
            color: #999;
            margin-bottom: 8px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }

        .info-block p {
            margin: 5px 0;
            font-weight: 500;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th {
            background: var(--primary-color);
            color: #fff;
            text-align: left;
            padding: 12px;
            font-size: 13px;
            text-transform: uppercase;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid var(--border-color);
        }

        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }

        .total-section {
            display: flex;
            justify-content: flex-end;
        }

        .total-table {
            width: 250px;
        }

        .total-table td {
            border: none;
            padding: 5px 12px;
        }

        .grand-total {
            background: #f9f9f9;
            font-size: 18px;
            color: var(--primary-color);
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }

        .barcode-section {
            margin-bottom: 20px;
        }

        .logistics-badge {
            display: inline-block;
            background: #e1f5fe;
            color: #01579b;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 11px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #2c3e50; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
            🖨️ CETAK SEKARANG
        </button>
        <p style="font-size: 12px; color: #666; margin-top: 5px;">Gunakan browser desktop untuk hasil PDF terbaik</p>
    </div>

    <div class="invoice-box">
        <div class="header">
            <div class="logo-section">
                <h1>AL'ASHAR PARFUM</h1>
                <p>Bekasi, West Java, Indonesia | 081394882490<br>www.ashargrosirparfum.com</p>
            </div>
            <div class="invoice-details">
                <h2>INVOICE GROSIR</h2>
                <p>No: <strong>{{ $order->invoice_number }}</strong></p>
                <p>Tgl: {{ $order->created_at->format('d M Y') }}</p>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-block">
                <h3>PENERIMA / SHIP TO</h3>
                <p><strong>{{ $order->recipient_name }}</strong></p>
                <p>{{ $order->recipient_phone }}</p>
                <p>{{ $order->shipping_address }}</p>
            </div>
            <div class="info-block">
                <h3>METODE PENGIRIMAN</h3>
                <p>Kurir: {{ $order->shipping_courier ?? 'Internal' }}</p>
                <p>P. Jawab: {{ $order->delivery_handler ?? '-' }}</p>
                <div class="logistics-badge">Estimasi Packing: {{ $order->packing_days ?? 1 }} Hari</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Item / Deskripsi</th>
                    <th class="text-right">Harga</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->details as $detail)
                <tr>
                    <td>
                        {{ $detail->product_name }}<br>
                        <small style="color: #666">{{ $detail->volume_ml ? $detail->volume_ml . ' ml' : '' }}</small>
                    </td>
                    <td class="text-right">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                    <td class="text-right">{{ $detail->quantity }}</td>
                    <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <table class="total-table">
                <tr>
                    <td>Subtotal:</td>
                    <td class="text-right">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Target Paket:</td>
                    <td class="text-right">Rp {{ number_format($order->package_target_amount, 0, ',', '.') }}</td>
                </tr>
                <tr class="grand-total font-bold">
                    <td>TOTAL AKHIR:</td>
                    <td class="text-right text-primary">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <div class="barcode-section">
                @if($order->barcode)
                    <div style="font-family: 'Libre Barcode 128', cursive; font-size: 40px; margin-bottom: 5px;">{{ $order->barcode }}</div>
                    <code>{{ $order->barcode }}</code>
                @endif
            </div>
            <p><strong>Terima kasih atas pesanan Grosir Anda!</strong></p>
            <p>Barang yang sudah dibeli tidak dapat ditukar atau dikembalikan kecuali ada perjanjian sebelumnya.</p>
            <p style="margin-top: 10px; font-style: italic;">Printed via APMS System at {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</body>
</html>
