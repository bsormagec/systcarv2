<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Observers\ProductObserver;
use App\Observers\AccountDetailObserver;
use App\Models\tenant\Product;
use App\Models\tenant\AccountDetail;
use Stancl\Tenancy\Contracts\TenancyBootstrapper;
use Illuminate\Support\Facades\Event;
use Inertia\Inertia;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
       product::observe(ProductObserver::class);
       AccountDetail::observe(AccountDetailObserver::class);
       config(['filesystems.disks.public.root' => storage_path('/app/public'),]);
       config(['filesystems.disks.public.url' => asset('/tenancy/assets'),]);
       Event::listen(TenancyBootstrapped::class, function (TenancyBootstrapped $event) {
            \Spatie\Permission\PermissionRegistrar::$cacheKey = 'spatie.permission.cache.tenant.' . $event->tenancy->tenant->id;
        });
        Inertia::share([
            "flash" => function () {
                return [
                    "status" => session("status"),
                    "success" => session("success"),
                    "error" => session("error"),
                ];
            },
            "request" => function () {
                return [
                    "token" => request()->route("token"),
                ];
            }
        ]);
    }
}
