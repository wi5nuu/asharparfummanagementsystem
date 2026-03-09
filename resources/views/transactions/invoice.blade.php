<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $transaction->invoice_number }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #FF6B35;
            padding-bottom: 10px;
        }
        .store-name {
            font-size: 24px;
            font-weight: bold;
            color: #FF6B35;
        }
        .details-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
        }
        .items-table th {
            background-color: #f2f2f2;
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .items-table td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        .totals {
            margin-top: 20px;
            text-align: right;
        }
        .totals-row {
            margin-bottom: 5px;
        }
        .grand-total {
            font-size: 18px;
            font-weight: bold;
            color: #FF6B35;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-style: italic;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <div class="store-name">ASHAR GROSIR PARFUM</div>
            <div>Bekasi, Indonesia | Telp: 0812-XXXX-XXXX</div>
        </div>

        <table class="details-table">
            <tr>
                <td>
                    <strong>Invoice:</strong> {{ $transaction->invoice_number }}<br>
                    <strong>Tanggal:</strong> {{ $transaction->created_at->format('d/m/Y H:i') }}
                </td>
                <td style="text-align: right;">
                    <strong>Pelanggan:</strong> {{ $transaction->customer->name ?? 'Umum' }}<br>
                    <strong>Kasir:</strong> {{ $transaction->user->name }}
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaction->details as $detail)
                <tr>
                    <td>
                        {{ $detail->product->name }}
                        @if($detail->bonus_quantity > 0)
                            <br><small style="color: #28a745;">+ Bonus: {{ $detail->bonus_quantity }} botol 20ml Sedang</small>
                        @endif
                    </td>
                    <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td style="text-align: right;">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <div class="totals-row">Subtotal: Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</div>
            @if($transaction->discount > 0)
            <div class="totals-row">Diskon: -Rp {{ number_format($transaction->discount, 0, ',', '.') }}</div>
            @endif
            <div class="totals-row grand-total">Total: Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</div>
            <div class="totals-row">Bayar: Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</div>
            <div class="totals-row">Kembali: Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</div>
        </div>

        <div class="footer">
            Terima kasih telah berbelanja di Ashar Grosir Parfum!
        </div>
    </div>
</body>
</html>
