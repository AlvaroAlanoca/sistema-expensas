<x-filament-panels::page>
    <!-- Cabecera: Selector de Caja y Saldo Actual -->
    <div class="flex flex-col md:flex-row justify-between items-center bg-white p-6 rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 gap-4">
        <div class="w-full md:w-1/2">
            <label class="text-sm font-medium leading-6 text-gray-950 dark:text-white">Filtrar por Caja / Cuenta</label>
            <select wire:model.live="cajaSeleccionada" class="block w-full mt-1 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-800 dark:border-white/10 dark:text-white">
                @foreach($cajas as $caja)
                    <option value="{{ $caja->id }}">Caja #{{ $caja->id }} (Apertura: {{ \Carbon\Carbon::parse($caja->created_at)->format('d/m/Y') }})</option>
                @endforeach
            </select>
        </div>
        <div class="text-center md:text-right">
            <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400">Saldo Disponible (Caja Actual)</h2>
            <p class="text-3xl font-bold text-primary-600 dark:text-primary-400">${{ number_format($saldoActual, 2) }}</p>
        </div>
    </div>

    <!-- Tabla Contable -->
    <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 overflow-hidden overflow-x-auto">
        <table class="w-full text-left text-sm divide-y divide-gray-200 dark:divide-white/5">
            <thead class="bg-gray-50 dark:bg-white/5">
                <tr>
                    <th class="px-4 py-3 font-medium text-gray-950 dark:text-white">Fecha</th>
                    <th class="px-4 py-3 font-medium text-gray-950 dark:text-white">Concepto</th>
                    <th class="px-4 py-3 font-medium text-emerald-600 dark:text-emerald-400 text-right">Ingreso (+)</th>
                    <th class="px-4 py-3 font-medium text-red-600 dark:text-red-400 text-right">Egreso (-)</th>
                    <th class="px-4 py-3 font-medium text-gray-950 dark:text-white text-right">Saldo Progresivo</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                @forelse($this->movimientos as $mov)
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($mov->fecha)->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-gray-950 dark:text-white font-medium">{{ $mov->concepto }}</td>
                        <td class="px-4 py-3 text-emerald-600 dark:text-emerald-400 text-right font-bold">
                            {{ $mov->ingreso > 0 ? '$' . number_format($mov->ingreso, 2) : '-' }}
                        </td>
                        <td class="px-4 py-3 text-red-600 dark:text-red-400 text-right font-bold">
                            {{ $mov->egreso > 0 ? '$' . number_format($mov->egreso, 2) : '-' }}
                        </td>
                        <td class="px-4 py-3 text-gray-950 dark:text-white text-right font-bold bg-gray-50/50 dark:bg-white/5">
                            ${{ number_format($mov->saldo, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                            No hay movimientos contables registrados en esta caja.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-filament-panels::page>