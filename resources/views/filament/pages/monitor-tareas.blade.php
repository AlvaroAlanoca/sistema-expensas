<x-filament-panels::page>
    <div class="bg-gray-900 text-green-400 p-6 rounded-xl font-mono text-sm overflow-x-auto shadow-lg" 
         wire:poll.5s="obtenerTareas">
         
        <div class="flex items-center gap-2 mb-4 text-white font-bold">
            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
            Terminal en vivo (Auto-recarga cada 5s)
        </div>

        <pre>{{ $salidaConsola ?: 'No hay tareas programadas en este momento.' }}</pre>
    </div>
</x-filament-panels::page>