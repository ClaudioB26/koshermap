<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (str_contains(config('app.url'), 'cloudworkstations.dev')) {
            URL::forceRootUrl(config('app.url'));
            URL::forceScheme('https');
        }

        // Contadores del menu del admin: cosas que esperan una accion (o, en
        // Leads, los de los ultimos 7 dias). Solo se calculan al renderizar ese layout.
        View::composer('layouts.admin-panel', function ($view) {
            $view->with('adminBadges', [
                'certifiers' => \App\Models\Certifier::where('status', \App\Models\Certifier::STATUS_PENDING)->count()
                    + \App\Models\CertifierTierPayment::where('payment_method', \App\Models\CertifierTierPayment::METHOD_TRANSFER)
                        ->where('status', \App\Models\CertifierTierPayment::STATUS_PENDING)->count(),
                'places'     => \App\Models\KosherPlace::where('status', \App\Models\KosherPlace::STATUS_PENDING)->count()
                    + \App\Models\PlaceTierPayment::where('payment_method', \App\Models\PlaceTierPayment::METHOD_TRANSFER)
                        ->where('status', \App\Models\PlaceTierPayment::STATUS_PENDING)->count(),
                'leads'      => \App\Models\CertifierLead::where('created_at', '>=', now()->subDays(7))->count(),
                'reports'    => \App\Models\Report::where('status', 'pending')->count(),
                'reviews'    => \App\Models\Review::where('status', \App\Models\Review::STATUS_PENDING)->count(),
            ]);
        });
    }
}
