<?php
// app/Providers/DiarioCajaServiceProvider.php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\DiarioCajaService;

class DiarioCajaServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(DiarioCajaService::class, function () {
            return new DiarioCajaService();
        });
    }

}
