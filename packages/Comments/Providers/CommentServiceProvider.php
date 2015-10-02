<?php

namespace PhpSoft\Comments\Providers;

use Illuminate\Support\ServiceProvider;
use PhpSoft\Comments\Commands\MigrationCommand;

class CommentServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     */
    public function boot()
    {
        // Set views path
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'phpsoft.comments');

        // Publish views
        $this->publishes([
            __DIR__ . '/../resources/views' => base_path('resources/views/vendor/phpsoft.comments'),
        ]);

        // Register commands
        $this->commands('phpsoft.comments.command.migration');
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommands();
    }

    /**
     * Register the artisan commands.
     *
     * @return void
     */
    private function registerCommands()
    {
        $this->app->bindShared('phpsoft.comments.command.migration', function () {
            return new MigrationCommand();
        });
    }
}
