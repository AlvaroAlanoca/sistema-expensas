<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Morosidad</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-8 text-gray-900 bg-white" onload="window.print()">
    <div class="mb-6 flex justify-between items-end border-b-2 border-gray-300 pb-4">
        <div>
            <h1 class="text-3xl font-bold">Reporte de Morosidad</h1>
            <p class="text-gray-500 mt-1">Gestión de Expensas</p>
        </div>
        <div class="text-right text-sm text-gray-600">
            <p>Fecha de emisión: {{ now()->format('d/m/Y') }}</p>
        </div>
    </div>

    <table class="w-full text-left text-sm border-collapse mt-4">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-3 border border-gray-300 text-gray-800">Propiedad</th>
                <th class="p-3 border border-gray-300 text-gray-800">Mes en Mora</th>
                <th class="p-3 border border-gray-300 text-gray-800">Vencimiento</th>
                <th class="p-3 border border-gray-300 text-red-700">Días de Retraso</th>
                <th class="p-3 border border-gray-300 text-right">Deuda Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($deudores as $deuda)
                <tr>
                    <td class="p-3 border border-gray-300 font-bold">Unidad {{ $deuda->propiedad->numero ?? 'N/A' }}</td>
                    <td class="p-3 border border-gray-300">{{ $deuda->mes_gestion ?: 'No especificado' }}</td>
                    <td class="p-3 border border-gray-300">{{ \Carbon\Carbon::parse($deuda->fecha_vencimiento)->format('d/m/Y') }}</td>
                    <td class="p-3 border border-gray-300 text-red-600 font-bold">{{ \Carbon\Carbon::parse($deuda->fecha_vencimiento)->diffInDays(\Carbon\Carbon::today()) }} días</td>
                    <td class="p-3 border border-gray-300 text-right font-bold">${{ number_format($deuda->monto_pendiente, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-500">No hay registros de morosidad.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>