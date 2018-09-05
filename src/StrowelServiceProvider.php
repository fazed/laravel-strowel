<?php

namespace Fazed\Strowel;

use Illuminate\Support\ServiceProvider;

class StrowelServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    protected $defer = true;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../resources/config/strowel.php.php' => config_path('strowel.php'),
        ], 'config');
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            'Fazed\Strowel\Contracts\BlockParserResultFactoryContract',
            'Fazed\Strowel\Factories\BlockParserResultFactory'
        );

        $this->app->bind(
            'Fazed\Strowel\Contracts\StringAnalyserContract',
            'Fazed\Strowel\StringAnalyser'
        );

        $this->app->bind(
            'Fazed\Strowel\Contracts\BlockParserContract',
            'Fazed\Strowel\Parsers\BlockParser'
        );

        $this->app->bind(
            'Fazed\Strowel\Contracts\BlockParserResultContract',
            'Fazed\Strowel\Parsers\BlockParserResult'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function provides()
    {
        return [
            'Fazed\Strowel\Contracts\BlockParserContract',
            'Fazed\Strowel\Contracts\StringAnalyserContract',
            'Fazed\Strowel\Contracts\BlockParserResultContract',
            'Fazed\Strowel\Contracts\BlockParserResultFactoryContract',
        ];
    }
}
