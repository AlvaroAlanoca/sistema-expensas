<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Artisan;

class MonitorTareas extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static string $view = 'filament.pages.monitor-tareas';
    protected static ?string $title = 'Monitor de Tareas';
    protected static ?string $navigationGroup = 'Administración';

    public string $salidaConsola = '';

    // Esta función se ejecuta al abrir la página y cada vez que Livewire la refresca
    public function obtenerTareas()
    {
        Artisan::call('schedule:list');
        $this->salidaConsola = Artisan::output();
    }

    public function mount()
    {
        $this->obtenerTareas();
    }
}