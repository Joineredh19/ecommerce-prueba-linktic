<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\Orden;

class OrdenesExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Orden::with('items.producto')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Número de Orden',
            'Estado',
            'Monto Total',
            'Productos',
            'Fecha de Creación',
            'Última Actualización'
        ];
    }

    public function map($orden): array
    {
        // Obtener lista de productos formateada
        $productos = $orden->items->map(function($item) {
            return sprintf(
                '%s (x%d)',
                $item->producto->nombre,
                $item->cantidad
            );
        })->implode(', ');

        return [
            $orden->id,
            $orden->numero_orden,
            $orden->estado,
            '$' . number_format($orden->monto_total, 2),
            $productos,
            $orden->created_at ? $orden->created_at->format('d/m/Y H:i:s') : 'N/A',
            $orden->updated_at ? $orden->updated_at->format('d/m/Y H:i:s') : 'N/A',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A1:G1' => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2E8F0']
                ]
            ]
        ];
    }
}
