<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Morosidad</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #1f2937; padding-bottom: 10px; }
        .title { font-size: 20px; font-weight: bold; margin: 0; color: #1f2937; }
        .subtitle { font-size: 12px; color: #6b7280; margin-top: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #f3f4f6; color: #1f2937; font-weight: bold; padding: 10px; text-align: left; border-bottom: 2px solid #d1d5db; }
        td { padding: 10px; border-bottom: 1px solid #e5e7eb; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-row { font-weight: bold; background-color: #f9fafb; }
        .deuda { color: #dc2626; font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        <p class="title">REPORTE DE MOROSIDAD Y DEUDORES</p>
        <p class="subtitle">Administración Edificio "SAN PEDRO" | Generado el: {{ $fechaReporte }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Unidad</th>
                <th>Inquilino / Responsable</th>
                <th>Mes Adeudado</th>
                <th class="text-center">Días de Retraso</th>
                <th class="text-right">Monto (Bs.)</th>
            </tr>
        </thead>
        <tbody>
            @php $totalDeuda = 0; @endphp
            
            @forelse($deudores as $deuda)
                @php $totalDeuda += $deuda->monto_pendiente; @endphp
                <tr>
                    <td>Unidad {{ $deuda->propiedad->numero ?? 'N/A' }}</td>
                    <td>{{ $deuda->propiedad->usuarios->first()?->name ?? 'Sin registrar' }} {{ $deuda->propiedad->usuarios->first()?->apellido_paterno ?? '' }}</td>
                    <td>{{ $deuda->mes_gestion }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($deuda->fecha_vencimiento)->diffInDays(\Carbon\Carbon::today()) }} días</td>
                    <td class="text-right deuda">Bs. {{ number_format($deuda->monto_pendiente, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No hay registros de morosidad en este momento.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4" class="text-right">TOTAL POR COBRAR:</td>
                <td class="text-right deuda">Bs. {{ number_format($totalDeuda, 2) }}</td>
            </tr>
        </tfoot>
    </table>

</body>
</html>