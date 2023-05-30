<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;

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
        Model::unguard();


        Sanctum::authenticateAccessTokensUsing(function (PersonalAccessToken $token, $isValid) {
            if($isValid) {
                return true;
            }
            return $token->can('remember') && $token->created_at->gt(now()->subYears(5));
        });


        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage())
            ->view('email.verify', ['url' => $url, 'name' => request()->input('name')])
                ->subject('Verify Email Address')
                ->line('Click the button below to verify your email address.')
                ->action('Verify Email Address', $url);
        });


    }
}
