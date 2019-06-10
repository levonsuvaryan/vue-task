<?php

namespace App\Providers;

use App\Models\Image;
use App\Models\Post;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $this->registerPermissions();

        Passport::routes(function ($router) {
            $router->forAccessTokens();
        });
    }

    private function registerPermissions(): void
    {
        Gate::define('manage-post', function (User $user, Post $post) {
            return $user->isAdmin() || $user->id === $post->user_id;
        });

        Gate::define('manage-image', function (User $user, Image $image) {
            return $user->isAdmin() || $user->id === $image->user_id;
        });

        Gate::define('manage-users', function (User $user) {
            return $user->isAdmin();
        });
    }

}
