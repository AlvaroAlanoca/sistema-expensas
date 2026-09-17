<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Balance General</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 14px; color: #333; }
        .header { text-align: center; margin-bottom: 40px; border-bottom: 3px solid #1f2937; padding-bottom: 15px; }
        .title { font-size: 24px; font-weight: bold; margin: 0; color: #1f2937; text-transform: uppercase; }
        .subtitle { font-size: 13px; color: #6b7280; margin-top: 5px; }
        
        .section { margin-bottom: 30px; }
        .section-title { font-size: 16px; font-weight: bold; background-color: #f3f4f6; padding: 10px; border-left: 4px solid #3b82f6; margin-bottom: 10px;}
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        td { padding: 8px 10px; border-bottom: 1px dashed #e5e7eb; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        
        .summary-box { border: 2px solid #1f2937; padding: 20px; text-align: center; background-color: #f9fafb; margin-top: 40px; }
        .summary-title { font-size: 18px; font-weight: bold; margin-bottom: 10px; }
        .summary-amount { font-size: 28px; font-weight: bold; }
        .positivo { color: #166534; }
        .negativo { color: #991b1b; }
    </style>
</head>
<body>

    <div class="header">
        <p class="title">REPORTE DE BALANCE GENERAL</p>
        <p class="subtitle">Administración Edificio "SAN PEDRO" | Fecha de Emisión: {{ $fechaReporte }}</p>
    </div>

    <div class="section">
        <div class="section-title" style="border-left-color: #10b981;">RESUMEN DE INGRESOS (ACTIVOS)</div>
        <table>
            <tr>
                <td>Recaudación por Expensas</td>
                <td class="text-right">Bs. {{ number_format($ingresosExpensas, 2) }}</td>
            </tr>
            <tr>
                <td>Recaudación por Servicio de Agua</td>
                <td class="text-right">Bs. {{ number_format($ingresosAgua, 2) }}</td>
            </tr>
            <tr>
                <td>Recaudación por Servicio de Gas</td>
                <td class="text-right">Bs. {{ number_format($ingresosGas, 2) }}</td>
            </tr>
            <tr>
                <td class="bold">TOTAL INGRESOS CONSOLIDADOS:</td>
                <td class="text-right bold" style="color: #10b981;">Bs. {{ number_format($totalIngresos, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title" style="border-left-color: #f97316;">RESUMEN DE EGRESOS (PASIVOS)</div>
        <table>
            <tr>
                <td>Gastos Generales del Edificio (Mantenimiento, Servicios, etc.)</td>
                <td class="text-right">Bs. {{ number_format($totalEgresos, 2) }}</td>
            </tr>
            <tr>
                <td class="bold">TOTAL EGRESOS CONSOLIDADOS:</td>
                <td class="text-right bold" style="color: #f97316;">Bs. {{ number_format($totalEgresos, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="summary-box">
        <div class="summary-title">BALANCE NETO ACTUAL (CAJA)</div>
        <div class="summary-amount {{ $balance >= 0 ? 'positivo' : 'negativo' }}">
            Bs. {{ number_format($balance, 2) }}
        </div>
    </div>

</body>
</html>