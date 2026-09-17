<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Gastos</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #9a3412; padding-bottom: 10px; }
        .title { font-size: 20px; font-weight: bold; margin: 0; color: #9a3412; }
        .subtitle { font-size: 12px; color: #6b7280; margin-top: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #ffedd5; color: #9a3412; font-weight: bold; padding: 10px; text-align: left; border-bottom: 2px solid #9a3412; }
        td { padding: 10px; border-bottom: 1px solid #e5e7eb; }
        .text-right { text-align: right; }
        .total-row { font-weight: bold; background-color: #fff7ed; }
        .negativo { color: #9a3412; font-weight: bold; font-size: 14px;}
        .capitalize { text-transform: capitalize; }
    </style>
</head>
<body>
    <div class="header">
        <p class="title">REPORTE DE GASTOS DEL EDIFICIO</p>
        <p class="subtitle">Administración Edificio "SAN PEDRO" | Generado el: {{ $fechaReporte }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>Mes / Gestión</th>
                <th class="text-right">Total Egresado (Bs.)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($gastos as $item)
                <tr>
                    <td class="capitalize">{{ $item->mes_legible }}</td>
                    <td class="text-right">Bs. {{ number_format($item->total_gastado, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" style="text-align: center;">No hay gastos registrados.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td class="text-right">GRAN TOTAL HISTÓRICO:</td>
                <td class="text-right negativo">Bs. {{ number_format($granTotal, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>