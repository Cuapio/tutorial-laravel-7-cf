<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Paypal;

class PaymentProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //De esta manera registramos nuestro modelo en el service Container
        $this->app->bind(Paypal::class, function ($app) {//igual podemos usar singleton
            return new Paypal(env('PAYPAL_ID'), env('PAYPAL_SECRET'));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
