<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        Password::defaults(function () {
            $rule = Password::min(8);

            return $this->app->isProduction()
                ? $rule->letters()->mixedCase()->numbers()->symbols()->uncompromised()
                : $rule;
        });

        Model::preventLazyLoading(! $this->app->isProduction());

        Model::preventSilentlyDiscardingAttributes(! $this->app->isProduction());

        Model::preventAccessingMissingAttributes(! $this->app->isProduction());

        DB::prohibitDestructiveCommands($this->app->isProduction());
    }
}
