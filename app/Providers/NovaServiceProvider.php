<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Augusto\Purchase\Purchase;
use Augusto\Appointments\Appointments;
use Illuminate\Support\Facades\Route;
use App\Nova\Metrics\SaleCount;
use App\Nova\Metrics\SalesPerDay;
use App\Nova\Metrics\SalesTypePayment;
use App\Nova\Metrics\SalesAverage;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        Nova::ignoreMigrations();
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                ->withAuthenticationRoutes([
                    // You can make this simpler by creating a tenancy route group
                    InitializeTenancyByDomain::class,
                    PreventAccessFromCentralDomains::class,
                    'web',
                ])
                ->withPasswordResetRoutes([
                    // You can make this simpler by creating a tenancy route group
                    InitializeTenancyByDomain::class,
                    PreventAccessFromCentralDomains::class,
                    'web',
                ])
                ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return in_array($user->email, [
                //
            ]);
           
        }); 
        if (!session()->exists('sucursal')) {
                session(['sucursal' => 1]);
        }
    }

    /**
     * Get the cards that should be displayed on the default Nova dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            new SaleCount,
            (new SalesPerDay)->width('2/3'),
            (new SalesTypePayment)->width('1/2'),
            (new SalesAverage)->width('1/2')
        ];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            \Vyuldashev\NovaPermission\NovaPermissionTool::make(),
            new Purchase,
            new Appointments
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
