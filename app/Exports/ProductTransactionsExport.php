<?php

namespace App\Exports;

use App\Models\ProductTransaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductTransactionsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return ProductTransaction::select('id', 'created_at', 'status', 'total_amount')
            ->with('transactionDetails')
            ->get()->map(function($trx) {
                return [
                    'id' => $trx->id,
                    'tanggal' => $trx->created_at->format("d M Y H:i"),
                    'status' => $trx->status,
                    'total' => $trx->total_amount,
                    'produk' => $trx->transactionDetails->map(function($d) { 
                        return $d->product->name . " (x" . $d->quantity . ")";
                    })->join(', ')
                ];
            });
    }
    
    public function headings(): array
    {
        return ["No", "Tanggal", "Status", "Total", "Produk"];
    }
}
