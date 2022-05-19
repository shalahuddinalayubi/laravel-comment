<?php

namespace Lara\Comment;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;

class CommentServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'comment');

        $this->mergeConfigFrom(__DIR__.'/../config/comment.php', 'comment');

        $this->setPolicies();

        if (config('comment.route')) {
            Route::group(['middleware' => ['web']], function () {
                $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
            });
        }

        // Blade::include('comment::comments.index', 'commentsIndex');

        $this->publishes([
            __DIR__.'/../config/comment.php' => config_path('comment.php')
        ], 'lara-comment-config');

        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations')
        ], 'lara-comment-migrations');

        $this->publishes([
            __DIR__.'/../resources/views/' => resource_path('views/vendor/comment'),
            __DIR__.'/../resources/js/components/' => resource_path('js/components/comment'),
        ], 'lara-comment-vue');

        $this->publishes([
            __DIR__.'/../resources/js/components/' => resource_path('js/components/comment')
        ], 'lara-comment-components');
    }

    public function setPolicies()
    {
        $this->policies[config('comment.comment')] = config('comment.policy');

        $this->registerPolicies();
    }
}
