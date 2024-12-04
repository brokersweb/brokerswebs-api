<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\LowStockReportMail;
use App\Models\Inventory\Material as InventoryMaterial;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Str;
class LowStockReportService
{
    /**
     * Genera un informe PDF de materiales con stock bajo y lo envía por correo
     *
     * @param int $threshold Umbral de stock mínimo (por defecto 10)
     * @return bool Indica si el reporte fue generado y enviado exitosamente
     */
    public function generateLowStockReport(int $threshold = 10, string $address): bool
    {

        try {
        // Consultar materiales con stock bajo
        $lowStockMaterials = InventoryMaterial::where('stock', '<=', $threshold)->get();

        // Verificar si hay materiales con stock bajo

        if ($lowStockMaterials->isEmpty()) {
            Log::info('No hay materiales con stock bajo');
            return false;
        }


        // Información de la empresa (configurable)
        $companyInfo = [
            'name' => 'Brokers Soluciones',
            'nit' => '43643534534534',
            'phone' => '(+57) 300-400-4272',
            'logo' =>public_path('assets/images/brand.png') // Ruta al logo

        ];

        // Generar PDF
        $pdf = Pdf::loadView('reports.low-stock', [
            'materials' => $lowStockMaterials,
            'companyInfo' => $companyInfo,
            'threshold' => $threshold
        ]);

        // Nombre de archivo único
        // $filename = 'reporte_stock_bajo_' . now()->format('YmdHis') . '.pdf';
        $filename = 'reporte_stock_bajo_' . Str::random(10) . '.pdf';

        // Asegurar que el directorio exista
        $reportPath = storage_path('app/reportes');
        if (!file_exists($reportPath)) {
            mkdir($reportPath, 0755, true);
        }


        // $path = Storage::path('reportes/' . $filename);
        $fullPath = $reportPath . '/' . $filename;
        // Guardar PDF
        $pdf->save($fullPath);

        if (!file_exists($fullPath)) {
            throw new Exception('No se pudo crear el archivo PDF');
        }

        // Enviar por correo
        Mail::to($address)
            ->send(new LowStockReportMail($fullPath, $filename));

        Log::info('Reporte de stock bajo generado y enviado', [
            'materiales' => $lowStockMaterials->count(),
            'archivo' => $filename
        ]);

        return true;
    } catch (\Exception $e) {
        // Registrar el error
        Log::error('Error al generar reporte de stock bajo', [
            'mensaje' => $e->getMessage(),
            'traza' => $e->getTraceAsString()
        ]);

        return false;
    }
}
}
