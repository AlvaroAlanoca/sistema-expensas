<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\FontProviders\GoogleFontProvider;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->renderHook(
        PanelsRenderHook::BODY_START,
        fn (): string => Blade::render('
            @if(request()->routeIs("filament.admin.auth.login"))
                <style>
                    body {
                        background-image: url("https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=2070&auto=format&fit=crop");
                        background-size: cover;
                        background-position: center;
                    }
                    /* Hacemos que la tarjeta de login sea semitransparente para un efecto elegante */
                    .fi-layout > div {
                        background-color: rgba(255, 255, 255, 0.9) !important;
                        backdrop-filter: blur(10px);
                        border-radius: 1rem;
                    }
                    .dark .fi-layout > div {
                        background-color: rgba(24, 24, 27, 0.85) !important;
                    }
                </style>
            @endif
        ')
    )
->colors([
    'primary' => Color::Indigo, // O usar un hexadecimal: '#1E3A8A'
    'gray' => Color::Slate,
    'danger' => Color::Rose,
    'info' => Color::Blue,
    'success' => Color::Emerald,
    'warning' => Color::Orange,
])

        ->brandName('Edifico San Pedro') 
        

        ->sidebarCollapsibleOnDesktop()
        
        ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
        ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
        ->pages([
            Pages\Dashboard::class,
        ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                //Widgets\AccountWidget::class,
                //Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
