<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Agua</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #1e3a8a; padding-bottom: 10px; }
        .title { font-size: 20px; font-weight: bold; margin: 0; color: #1e3a8a; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #dbeafe; color: #1e3a8a; padding: 10px; text-align: left; border-bottom: 2px solid #93c5fd; }
        td { padding: 10px; border-bottom: 1px solid #e5e7eb; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <p class="title">REPORTE HISTÓRICO - CONSUMO DE AGUA</p>
        <p style="font-size: 12px; color: #6b7280;">Administración Edificio "SAN PEDRO" | Fecha: {{ $fechaReporte }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>Mes / Gestión</th>
                <th>Propiedad</th>
                <th class="text-right">Consumo (m³)</th>
                <th class="text-right">Total (Bs.)</th>
                <th class="text-center">Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($lecturas as $item)
                <tr>
                    <td>{{ $item->mes_gestion }}</td>
                    <td>Unidad {{ $item->propiedad->id ?? 'N/A' }}</td>
                    <td class="text-right">{{ $item->consumo_m3 }} m³</td>
                    <td class="text-right">Bs. {{ number_format($item->monto_total, 2) }}</td>
                    <td class="text-center" style="color: {{ $item->estado == 1 ? '#166534' : '#991b1b' }}; font-weight: bold;">
                        {{ $item->estado == 1 ? 'Pagado' : 'Pendiente' }}
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">No hay registros.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>