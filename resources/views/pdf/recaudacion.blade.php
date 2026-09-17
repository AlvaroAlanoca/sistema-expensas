<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Recaudación</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #166534; padding-bottom: 10px; }
        .title { font-size: 20px; font-weight: bold; margin: 0; color: #166534; }
        .subtitle { font-size: 12px; color: #6b7280; margin-top: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #dcfce7; color: #166534; font-weight: bold; padding: 10px; text-align: left; border-bottom: 2px solid #166534; }
        td { padding: 10px; border-bottom: 1px solid #e5e7eb; }
        .text-right { text-align: right; }
        .total-row { font-weight: bold; background-color: #f0fdf4; }
        .positivo { color: #166534; font-weight: bold; font-size: 14px;}
    </style>
</head>
<body>
    <div class="header">
        <p class="title">REPORTE DE RECAUDACIÓN MENSUAL</p>
        <p class="subtitle">Administración Edificio "SAN PEDRO" | Generado el: {{ $fechaReporte }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>Mes / Gestión</th>
                <th class="text-right">Monto Consolidado (Bs.)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recaudaciones as $item)
                <tr>
                    <td>{{ $item->mes_gestion }}</td>
                    <td class="text-right">Bs. {{ number_format($item->total_recaudado, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" style="text-align: center;">No hay recaudaciones registradas.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td class="text-right">GRAN TOTAL HISTÓRICO:</td>
                <td class="text-right positivo">Bs. {{ number_format($granTotal, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>