<x-filament-panels::page>
    @php $datos = $this->deudaData; @endphp

    @if($datos['propiedades']->isEmpty())
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md dark:bg-yellow-900/30">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700 dark:text-yellow-200">
                        Aún no tienes propiedades asignadas a tu cuenta. Contacta a la administración.
                    </p>
                </div>
            </div>
        </div>
    @else
        <!-- Tarjeta Principal de Deuda Total -->
        <div class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 text-center mb-6">
            <h2 class="text-lg font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Deuda Total Pendiente</h2>
            <span class="text-4xl font-black {{ $datos['total_general'] > 0 ? 'text-red-600' : 'text-green-600' }}">
                Bs. {{ number_format($datos['total_general'], 2) }}
            </span>
            @if($datos['total_general'] == 0)
                <p class="mt-4 text-sm font-medium text-green-600">¡Felicidades! Estás al día con todos tus pagos.</p>
            @endif
        </div>

        <!-- Desglose por Categorías -->
        @if($datos['total_general'] > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Expensas -->
                <div class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 border-t-4 border-blue-500">
                    <h3 class="font-bold text-gray-900 dark:text-white mb-4">Expensas Atrasadas</h3>
                    <p class="text-2xl font-bold text-blue-600 mb-4">Bs. {{ number_format($datos['expensas'], 2) }}</p>
                    <ul class="text-sm space-y-2 text-gray-600 dark:text-gray-400">
                        @foreach($datos['detalles_expensas'] as $exp)
                            <li class="flex justify-between border-b border-gray-100 dark:border-gray-800 pb-1">
                                <span>{{ $exp->mes_gestion }}</span>
                                <span>Bs. {{ number_format($exp->monto_pendiente, 2) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Agua -->
                <div class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 border-t-4 border-cyan-500">
                    <h3 class="font-bold text-gray-900 dark:text-white mb-4">Servicio de Agua</h3>
                    <p class="text-2xl font-bold text-cyan-600 mb-4">Bs. {{ number_format($datos['agua'], 2) }}</p>
                    <ul class="text-sm space-y-2 text-gray-600 dark:text-gray-400">
                        @foreach($datos['detalles_agua'] as $agua)
                            <li class="flex justify-between border-b border-gray-100 dark:border-gray-800 pb-1">
                                <span>{{ $agua->mes_gestion }}</span>
                                <span>Bs. {{ number_format($agua->monto_pendiente, 2) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Gas -->
                <div class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 border-t-4 border-orange-500">
                    <h3 class="font-bold text-gray-900 dark:text-white mb-4">Servicio de Gas</h3>
                    <p class="text-2xl font-bold text-orange-600 mb-4">Bs. {{ number_format($datos['gas'], 2) }}</p>
                    <ul class="text-sm space-y-2 text-gray-600 dark:text-gray-400">
                        @foreach($datos['detalles_gas'] as $gas)
                            <li class="flex justify-between border-b border-gray-100 dark:border-gray-800 pb-1">
                                <span>{{ $gas->mes_gestion }}</span>
                                <span>Bs. {{ number_format($gas->monto_pendiente, 2) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

            </div>
        @endif
    @endif
</x-filament-panels::page>