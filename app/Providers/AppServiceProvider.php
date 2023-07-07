<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 */
	public function register(): void
	{
	}

	/**
	 * Bootstrap any application services.
	 */
	public function boot(): void
	{
		VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
			$url = env('FRONT_APP_URL', 'http://epic-movie-quotes-front.esaiag.redberryinternship.ge') . '/email-verify?url=' . $url . '&email=' . request()->input('email');
			return (new MailMessage())
			->view('email.verify', ['url' => $url, 'name' => request()->input('name')])
				->subject('Verify Email Address')
				->line('Click the button below to verify your email address.')
				->action('Verify Email Address', $url);
		});

		ResetPassword::toMailUsing(function (object $notifiable, string $token) {
			$email = request()->input('email');
			$name = User::where('email', $email)->first()->name;
			$url = env('FRONT_APP_URL', 'http://epic-movie-quotes-front.esaiag.redberryinternship.ge/
') . '/reset-password?token=' . $token . '&email=' . $email;
			return (new MailMessage())
			->view('email.reset', ['url' => $url, 'name' => $name])
				->subject('Recover password');
		});
	}
}
