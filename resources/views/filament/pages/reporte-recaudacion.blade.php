<x-filament-panels::page>
    <!-- Botón de Exportación (Lo conectaremos a PDF más adelante) -->
    <div class="flex justify-end mb-4">
<a href="{{ route('imprimir.recaudacion') }}" target="_blank" class="flex items-center gap-2 bg-primary-600 hover:bg-primary-500 text-white font-bold py-2 px-4 rounded-lg shadow-sm transition-all text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Imprimir / Descargar PDF
        </a>
    </div>

    <!-- Tabla de Recaudación -->
    <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 overflow-hidden overflow-x-auto">
        <table class="w-full text-left text-sm divide-y divide-gray-200 dark:divide-white/5">
            <thead class="bg-green-50 dark:bg-green-900/20">
                <tr>
                    <th class="px-4 py-3 font-medium text-green-800 dark:text-green-400">Mes / Gestión</th>
                    <th class="px-4 py-3 font-medium text-gray-950 dark:text-white text-right">Total Recaudado</th>
                    <th class="px-4 py-3 font-medium text-gray-950 dark:text-white text-center">Estado</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                @php $granTotal = 0; @endphp

                @forelse($this->recaudaciones as $item)
                    @php $granTotal += $item->total_recaudado; @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                        <td class="px-4 py-3 text-gray-950 dark:text-white font-bold">
                            {{ $item->mes_gestion }}
                        </td>
                        <td class="px-4 py-3 text-gray-950 dark:text-white text-right font-bold text-lg">
                            Bs. {{ number_format($item->total_recaudado, 2) }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2 py-1 text-xs font-semibold text-green-600 ring-1 ring-inset ring-green-600/20">
                                Ingreso Consolidado
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400 font-medium">
                            No hay recaudaciones registradas todavía.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="bg-gray-50 dark:bg-white/5">
                    <td class="px-4 py-3 font-bold text-gray-950 dark:text-white text-right uppercase">Total Histórico:</td>
                    <td class="px-4 py-3 font-bold text-green-600 dark:text-green-400 text-right text-xl">
                        Bs. {{ number_format($granTotal, 2) }}
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</x-filament-panels::page>