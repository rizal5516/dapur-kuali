<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Auth\Notifications\ResetPassword;

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
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->input('email', '');
            return Limit::perMinute(10, 5)->by('login:' . $request->ip() . ':' . $email);
        });

        RateLimiter::for('password-reset', function (Request $request) {
            $key = 'pwreset:'.sha1(($request->ip() ?? 'ip').':'.(string) $request->input('email'));
            return Limit::perMinute(5)->by($key);
        });

        RateLimiter::for('wedding-reservations', function (Request $request) {
            $key = 'wedding' . $request->ip();

            return Limit::perMinute(10, 5)->by($key);
        });

        ResetPassword::createUrlUsing(function ($user, string $token) {
            $base = rtrim(config('app.frontend_url'), '/');


            return $base.'/admin/reset-password'
            .'?token='.$token
            .'&email='.urlencode($user->email);
        });
    }
}
