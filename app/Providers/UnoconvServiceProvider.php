<?php

namespace App\Providers;

use Unoconv\Unoconv;
use Laravel\Lumen\Application;
use Illuminate\Support\ServiceProvider;

class UnoconvServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app['unoconv.default.configuration'] = [
            'unoconv.binaries' => ['unoconv'],
            'timeout'          => 120,
        ];
        $this->app['unoconv.configuration'] = [];
        $this->app['unoconv.logger'] = null;

        $this->app->singleton(Unoconv::class, function(Application $app) {
            $app['unoconv.configuration'] = array_replace(
                $app['unoconv.default.configuration'], $app['unoconv.configuration']
            );

            return Unoconv::create($app['unoconv.configuration'], $app['unoconv.logger']);
        });
    }
}
