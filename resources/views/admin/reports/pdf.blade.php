<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan Mahesty Mebel</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #444;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            text-transform: uppercase;
            color: #0e8f2c;
        }
        .header p {
            margin: 5px 0;
            font-size: 12px;
            color: #666;
        }
        .report-info {
            margin-bottom: 20px;
        }
        .report-info table {
            width: 100%;
        }
        .report-info td {
            font-size: 13px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .table th {
            background-color: #0e8f2c;
            color: white;
            text-align: left;
            padding: 10px;
            font-size: 12px;
            text-transform: uppercase;
        }
        .table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            font-size: 12px;
            vertical-align: top;
        }
        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .total-section {
            float: right;
            width: 250px;
        }
        .total-table {
            width: 100%;
            border-top: 2px solid #444;
        }
        .total-table td {
            padding: 8px 0;
            font-size: 14px;
        }
        .total-table .label {
            font-weight: bold;
        }
        .total-table .value {
            text-align: right;
            font-weight: bold;
            color: #0e8f2c;
        }
        .footer {
            margin-top: 50px;
            text-align: right;
            font-size: 12px;
        }
        .signature {
            margin-top: 80px;
            font-weight: bold;
        }
        .page-number:after {
            content: counter(page);
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>MAHESTY MEBEL</h1>
        <p>Jl. Raya tajem Sleman, DIY</p>
        <p>Email:mahestymebel@gmail.com | Telp: 08156580019</p>
    </div>

    <div class="report-info">
        <h2 style="text-align: center; font-size: 18px; margin-bottom: 15px;">LAPORAN PENJUALAN</h2>
        <table>
            <tr>
                <td><strong>Tanggal Cetak:</strong> {{ date('d F Y H:i') }}</td>
                <td style="text-align: right;">
                    <strong>Periode:</strong> 
                    @if($startDate && $endDate)
                        {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
                    @else
                        Semua Transaksi Lunas
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">ID Order</th>
                <th width="15%">Tanggal</th>
                <th width="20%">Pelanggan</th>
                <th width="30%">Item Pesanan</th>
                <th width="15%">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $totalRevenue = 0; @endphp
            @foreach($orders as $index => $order)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>#{{ $order->id }}</strong></td>
                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                    <td>{{ $order->user->name ?? 'Guest' }}</td>
                    <td>
                        @foreach($order->orderItems as $item)
                            • {{ $item->productItem->name ?? 'Produk Dihapus' }} (x{{ $item->quantity }})<br>
                        @endforeach
                    </td>
                    <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                </tr>
                @php $totalRevenue += $order->total; @endphp
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <table class="total-table">
            <tr>
                <td class="label">TOTAL PENDAPATAN</td>
                <td class="value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div style="clear: both;"></div>

    <div class="footer">
        <p>Sleman, {{ date('d F Y') }}</p>
        <p>Hormat Kami,</p>
        <div class="signature">
            <p>( Admin Mahesty Mebel )</p>
        </div>
    </div>

    <script type="text/php">
        if ( isset($pdf) ) {
            $font = $fontMetrics->get_font("helvetica", "bold");
            $pdf->page_text(520, 800, "Halaman: {PAGE_NUM} dari {PAGE_COUNT}", $font, 8, array(0,0,0));
        }
    </script>
</body>
</html>
