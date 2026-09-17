<x-filament-panels::page>
    <!-- Botón de Exportación -->
    <div class="flex justify-end mb-4">
        <a href="{{ route('imprimir.balance') }}" target="_blank" class="flex items-center gap-2 bg-primary-600 hover:bg-primary-500 text-white font-bold py-2 px-4 rounded-lg shadow-sm transition-all text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Imprimir Balance (PDF)
        </a>
    </div>

    @php $datos = $this->balanceData; @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Tarjeta Ingresos -->
        <div class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10">
            <h3 class="text-lg font-bold text-green-600 dark:text-green-400 mb-4">Total Ingresos</h3>
            <ul class="space-y-3 text-sm text-gray-600 dark:text-gray-400 mb-4">
                <li class="flex justify-between"><span>Cobro Expensas:</span> <span class="font-bold">Bs. {{ number_format($datos['expensas'], 2) }}</span></li>
                <li class="flex justify-between"><span>Cobro Agua:</span> <span class="font-bold">Bs. {{ number_format($datos['agua'], 2) }}</span></li>
                <li class="flex justify-between"><span>Cobro Gas:</span> <span class="font-bold">Bs. {{ number_format($datos['gas'], 2) }}</span></li>
            </ul>
            <div class="pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <span class="font-bold text-gray-900 dark:text-white">TOTAL:</span>
                <span class="text-xl font-bold text-green-600">Bs. {{ number_format($datos['total_ingresos'], 2) }}</span>
            </div>
        </div>

        <!-- Tarjeta Egresos -->
        <div class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10">
            <h3 class="text-lg font-bold text-orange-600 dark:text-orange-400 mb-4">Total Egresos</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Suma de todos los gastos operativos, mantenimiento y servicios comunes del edificio.</p>
            <div class="pt-4 mt-auto border-t border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <span class="font-bold text-gray-900 dark:text-white">TOTAL:</span>
                <span class="text-xl font-bold text-orange-600">Bs. {{ number_format($datos['total_egresos'], 2) }}</span>
            </div>
        </div>

        <!-- Tarjeta Balance Neto -->
        <div class="bg-{{ $datos['neto'] >= 0 ? 'green' : 'red' }}-50 dark:bg-gray-800 rounded-xl p-6 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 border-l-4 border-{{ $datos['neto'] >= 0 ? 'green' : 'red' }}-500 flex flex-col justify-center items-center text-center">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">BALANCE NETO (CAJA)</h3>
            <span class="text-3xl font-black {{ $datos['neto'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                Bs. {{ number_format($datos['neto'], 2) }}
            </span>
            <p class="mt-4 text-sm font-medium {{ $datos['neto'] >= 0 ? 'text-green-700' : 'text-red-700' }}">
                {{ $datos['neto'] >= 0 ? 'Superávit / Finanzas Saludables' : 'Déficit / Revisar Gastos' }}
            </p>
        </div>
    </div>
</x-filament-panels::page>