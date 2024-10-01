<?php

namespace App\Filament\Exports;

use Filament\Actions\Exports\Models\Export;

trait ExportConcerns
{
    public static function getCompletedNotificationBody(Export $export): string
    {
        $type = self::$type ?? 'registros';

        $body = 'Sua exportação de '.$type.' foi concluída. '.number_format($export->successful_rows).' '.str('linha')->plural($export->successful_rows).' '.str('exportada')->plural($export->successful_rows);

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('linha')->plural($failedRowsCount).' erro ao exportar.';
        }

        return $body;
    }
}
