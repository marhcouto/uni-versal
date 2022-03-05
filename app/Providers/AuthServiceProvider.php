<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
      'App\Models\Answer' => 'App\Policies\AnswerPolicy',
      'App\Models\Question' => 'App\Policies\QuestionPolicy',
      'App\Models\Post' => 'App\Policies\PostPolicy',
      'App\Models\User' => 'App\Policies\UserPolicy',
      'App\Models\Topic' => 'App\Policies\TopicPolicy'
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        VerifyEmail::toMailUsing(function ($notifiable, $url) {
          return (new MailMessage)
              ->subject('UNI-Versal Account Verification')
              ->line('Click the button below to verify your account on UNI-Versal. If you have not requested an account creation, do not click the link.')
              ->action('Verify Email Address', $url);
        });
    }
}
