<?php

namespace App\Exports;

use App\Models\MasterItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MasterItemsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return MasterItem::with(['categories'])->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Kategori',
            'Nama Items',
            'Nama Supplier',
            'Harga',
            'Laba',
            'Harga Jual',
        ];
    }

    public function map($item): array
    {
        $categories = $item->categories->pluck('nama')->join(', ');
        // Calculate Harga Jual (Harga Beli + (Harga Beli * Laba / 100))
        $hargaJual = $item->harga_beli + ($item->harga_beli * $item->laba / 100);

        return [
            $item->id,
            $categories,
            $item->nama,
            $item->supplier,
            $item->harga_beli,
            $item->laba,
            $hargaJual,
        ];
    }
}
