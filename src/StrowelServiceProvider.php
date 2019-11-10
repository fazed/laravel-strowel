<?php

namespace Fazed\Strowel;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class StrowelServiceProvider extends ServiceProvider implements DeferrableProvider
{
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
     * {@inheritDoc}
     */
    public function register()
    {
        $this->app->singleton(
            \Fazed\Strowel\Contracts\BlockParserResultFactoryContract::class,
            \Fazed\Strowel\Factories\BlockParserResultFactory::class
        );

        $this->app->bind(
            \Fazed\Strowel\Contracts\StringAnalyserContract::class,
            \Fazed\Strowel\StringAnalyser::class
        );

        $this->app->bind(
            \Fazed\Strowel\Contracts\BlockParserContract::class,
            \Fazed\Strowel\Parsers\BlockParser::class
        );

        $this->app->bind(
            \Fazed\Strowel\Contracts\BlockParserResultContract::class,
            \Fazed\Strowel\Parsers\BlockParserResult::class
        );
    }

    /**
     * {@inheritdoc}
     */
    public function provides()
    {
        return [
            \Fazed\Strowel\Contracts\BlockParserContract::class,
            \Fazed\Strowel\Contracts\StringAnalyserContract::class,
            \Fazed\Strowel\Contracts\BlockParserResultContract::class,
            \Fazed\Strowel\Contracts\BlockParserResultFactoryContract::class,
        ];
    }
}
