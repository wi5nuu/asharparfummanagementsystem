<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt #{{ $transaction->invoice_number }}</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'Courier', 'Courier New', monospace;
            font-size: 11px;
            width: 72mm; /* Narrow for thermal print */
            margin: 0;
            padding: 5mm;
            color: #000;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        
        .header {
            margin-bottom: 5px;
        }
        .store-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 2px;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .items td {
            padding: 2px 0;
            vertical-align: top;
        }
        .totals td {
            padding: 1px 0;
        }
        
        .footer {
            margin-top: 10px;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header text-center">
        <div class="store-name">AL'ASHAR PARFUM</div>
        <div>Bekasi, Indonesia | 081394882490</div>
        <div>www.ashargrosirparfum.com</div>
    </div>

    <div class="divider"></div>

    <table>
        <tr>
            <td>No : {{ $transaction->invoice_number }}</td>
        </tr>
        <tr>
            <td>Tgl: {{ $transaction->created_at->format('d/m/y H:i') }}</td>
        </tr>
        <tr>
            <td>Ksr: {{ substr($transaction->user->name, 0, 10) }}</td>
        </tr>
        <tr>
            <td>Plg: {{ substr($transaction->customer->name ?? 'Umum', 0, 15) }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <table class="items">
        @php $totalSavings = 0; @endphp
        @foreach($transaction->details as $detail)
            @php 
                $isBonus = ($detail->price == 0);
                $displayPrice = $isBonus ? 43333 : $detail->price;
                $displaySubtotal = $isBonus ? ($detail->quantity * 43333) : $detail->subtotal;
                if ($isBonus) {
                    $totalSavings += ($detail->quantity * 43333);
                }
            @endphp
            
            @if($isBonus && $transaction->receipt_visibility === 'private')
                @continue
            @endif
        <tr>
            <td colspan="3">
                {{ $detail->product->name }}
                @if($detail->size) ({{ $detail->size }}) @endif
                @if($isBonus) <span style="font-size: 8px;">(FREE)</span> @endif
            </td>
        </tr>
        <tr>
            <td width="30%">{{ $detail->quantity }} x</td>
            <td width="30%">{{ number_format($displayPrice, 0, ',', '.') }}</td>
            <td class="text-right">{{ number_format($displaySubtotal, 0, ',', '.') }}</td>
        </tr>
        @if($isBonus)
        <tr>
            <td colspan="2" style="font-size: 9px; padding-left: 5px; font-style: italic;">* Potongan Bonus</td>
            <td class="text-right" style="font-size: 9px; font-style: italic;">-{{ number_format($displaySubtotal, 0, ',', '.') }}</td>
        </tr>
        @endif
        @endforeach
    </table>

    <div class="divider"></div>

    <table class="totals">
        <tr>
            <td>Subtotal</td>
            <td class="text-right">{{ number_format($transaction->subtotal, 0, ',', '.') }}</td>
        </tr>
        @if($transaction->discount > 0)
        <tr>
            <td>
                Diskon 
                @if($transaction->discount_type === 'percent')
                    ({{ number_format($transaction->discount_percent, 0) }}%)
                @endif
            </td>
            <td class="text-right">
                @if($transaction->discount_type === 'percent')
                    -{{ number_format($transaction->discount_percent, 0) }}%
                @else
                    -{{ number_format($transaction->discount, 0, ',', '.') }}
                @endif
            </td>
        </tr>
        @endif
        <tr>
            <td>PPN (10%)</td>
            <td class="text-right">{{ number_format($transaction->tax_amount, 0, ',', '.') }}</td>
        </tr>
        <tr class="bold">
            <td>TOTAL</td>
            <td class="text-right">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="2"><div class="divider"></div></td>
        </tr>
        <tr>
            <td>Bayar</td>
            <td class="text-right">{{ number_format($transaction->paid_amount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Kembali</td>
            <td class="text-right">{{ number_format($transaction->change_amount, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="divider"></div>
    
    @if($totalSavings > 0 && ($transaction->receipt_visibility ?? 'public') === 'public')
    <div style="text-align: center; margin: 10px 0; font-weight: bold; border: 1px dashed #000; padding: 5px;">
        *** HEMAT: Rp {{ number_format($totalSavings, 0, ',', '.') }} ***<br>
        <small style="font-weight: normal; font-size: 8px;">(Nilai Bonus Aroma 20ml)</small>
    </div>
    @endif
    
    <div class="footer text-center">
        *** TERIMA KASIH ***<br>
        Barang yang sudah dibeli<br>
        tidak dapat ditukar/dikembalikan
        
        <div style="margin-top: 15px;">
            @if(isset($qrBase64))
                <!-- QR Code for digital link -->
                <img src="{{ $qrBase64 }}" style="width: 100px; height: 100px; margin-bottom: 5px;">
                <div style="font-size: 8px; margin-bottom: 10px;">Scan untuk Cek Keaslian</div>
            @endif

            @if(isset($barcodeBase64))
                <!-- Improved Barcode (Code 128) -->
                <img src="{{ $barcodeBase64 }}" style="max-width: 100%; height: 50px;">
                <div style="font-size: 10px; margin-top: 2px; font-weight: bold;">{{ $transaction->invoice_number }}</div>
            @endif
        </div>
    </div>
</body>
</html>

