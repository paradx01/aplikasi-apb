<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan Apotek</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #444; padding: 8px; text-align: left; }
        th { background: #f2f2f2; font-weight: bold; }
        h2 { margin-bottom: 5px; }
    </style>
</head>
<body>
    <h2>Laporan Penjualan Apotek</h2>
    <p>Tanggal Cetak: {{ now()->format('d M Y H:i') }}</p>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Total</th>
                <th>Produk</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $n => $trx)
                <tr>
                    <td>{{ $n + 1 }}</td>
                    <td>{{ $trx->created_at->format("d M Y H:i") }}</td>
                    <td>{{ strtoupper($trx->status) }}</td>
                    <td>Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                    <td>
                        @foreach($trx->transactionDetails as $det)
                            {{ $det->product->name }} (x{{ $det->quantity }})<br>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
