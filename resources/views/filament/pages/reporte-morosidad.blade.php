<x-filament-panels::page>
    <div class="flex justify-end mb-4">
        <a href="{{ route('imprimir.morosidad') }}" target="_blank" class="flex items-center gap-2 bg-primary-600 hover:bg-primary-500 text-white font-bold py-2 px-4 rounded-lg shadow-sm transition-all text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Imprimir / Descargar PDF
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 overflow-hidden overflow-x-auto">
        <table class="w-full text-left text-sm divide-y divide-gray-200 dark:divide-white/5">
            <thead class="bg-red-50 dark:bg-red-900/20">
                <tr>
                    <th class="px-4 py-3 font-medium text-red-800 dark:text-red-400">Propiedad</th>
                    <th class="px-4 py-3 font-medium text-gray-950 dark:text-white">Teléfono</th>
                    <th class="px-4 py-3 font-medium text-gray-950 dark:text-white">Mes en Mora</th>
                    <th class="px-4 py-3 font-medium text-gray-950 dark:text-white">Vencimiento</th>
                    <th class="px-4 py-3 font-medium text-red-600 dark:text-red-400 font-bold">Días de Retraso</th>
                    <th class="px-4 py-3 font-medium text-gray-950 dark:text-white text-right">Deuda Total</th>
                    <th class="px-4 py-3 font-medium text-gray-950 dark:text-white text-center print:hidden">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                @forelse($this->deudores as $deuda)
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                        <td class="px-4 py-3 text-gray-950 dark:text-white font-bold">
                            Unidad {{ $deuda->propiedad->numero ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400">
                            {{-- AQUÍ EXTRAEMOS EL TELÉFONO DE FORMA DIRECTA Y SEGURA --}}
                            {{ $deuda->propiedad->usuarios->first()?->telefono ?? 'Sin registrar' }}
                        </td>
                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $deuda->mes_gestion }}</td>
                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($deuda->fecha_vencimiento)->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-red-600 dark:text-red-400 font-bold">
                            {{ \Carbon\Carbon::parse($deuda->fecha_vencimiento)->diffInDays(\Carbon\Carbon::today()) }} días
                        </td>
                        <td class="px-4 py-3 text-gray-950 dark:text-white text-right font-bold">
                            Bs. {{ number_format($deuda->monto_pendiente, 2) }}
                        </td>
                        <td class="px-4 py-3 text-center print:hidden">
                            <button 
                                wire:click="enviarWhatsApp({{ $deuda->id }})"
                                wire:loading.attr="disabled"
                                class="inline-flex items-center gap-1 bg-green-500 hover:bg-green-600 text-white font-bold py-1 px-3 rounded text-xs transition-colors disabled:opacity-50"
                            >
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.888-.788-1.489-1.761-1.663-2.06-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                                Enviar Aviso
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-emerald-600 dark:text-emerald-400 font-medium">
                            No hay inquilinos morosos en este momento. ¡Excelente!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-filament-panels::page>