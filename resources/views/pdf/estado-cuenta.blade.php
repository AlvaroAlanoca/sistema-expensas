<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estado de Cuenta</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #333; }
        .header { border-bottom: 2px solid #1f2937; padding-bottom: 10px; margin-bottom: 20px; }
        .title { font-size: 22px; font-weight: bold; margin: 0; color: #1f2937; text-align: center; }
        .info-box { margin-bottom: 20px; background-color: #f9fafb; padding: 15px; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #f3f4f6; color: #1f2937; font-weight: bold; padding: 8px; text-align: left; border-bottom: 2px solid #d1d5db; }
        td { padding: 8px; border-bottom: 1px solid #e5e7eb; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .badge-pagado { color: #059669; font-weight: bold; }
        .badge-deuda { color: #dc2626; font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        <p class="title">ESTADO DE CUENTA INDIVIDUAL</p>
        <p class="text-center" style="color: #6b7280; margin-top: 5px;">Administración Edificio "SAN PEDRO" | Fecha: {{ $fechaReporte }}</p>
    </div>

    <div class="info-box">
        <strong>Unidad / Departamento:</strong> {{ $propiedad->numero }} {{ $propiedad->torre ? '(Torre '.$propiedad->torre.')' : '' }}<br><br>
        <strong>Responsable actual:</strong> {{ $propiedad->usuarios->first()?->name ?? 'Sin asignar' }} {{ $propiedad->usuarios->first()?->apellido_paterno ?? '' }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Mes / Gestión</th>
                <th>Vencimiento</th>
                <th class="text-right">Monto Total</th>
                <th class="text-right">Pagado</th>
                <th class="text-right">Saldo Pendiente</th>
                <th class="text-center">Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($propiedad->expensas as $expensa)
                <tr>
                    <td>{{ $expensa->mes_gestion }}</td>
                    <td>{{ \Carbon\Carbon::parse($expensa->fecha_vencimiento)->format('d/m/Y') }}</td>
                    <td class="text-right">Bs. {{ number_format($expensa->monto, 2) }}</td>
                    <td class="text-right">Bs. {{ number_format($expensa->monto_pagado ?? 0, 2) }}</td>
                    <td class="text-right">Bs. {{ number_format($expensa->monto_pendiente, 2) }}</td>
                    <td class="text-center">
                        @if($expensa->monto_pendiente <= 0)
                            <span class="badge-pagado">PAGADO</span>
                        @else
                            <span class="badge-deuda">DEUDA</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Esta unidad no tiene historial de expensas registrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>