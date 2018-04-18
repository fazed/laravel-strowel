<?php

namespace Fazed\TorrentTitleParser;

use Illuminate\Support\ServiceProvider;

class TorrentTitleParserProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../resources/config/torrent-title-parser.php',
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
            'Fazed\TorrentTitleParser\Contracts\BlockFactoryContract',
            'Fazed\TorrentTitleParser\Factories\BlockFactory'
        );

        $this->app->bind(
            'Fazed\TorrentTitleParser\Contracts\StringAnalyserContract',
            'Fazed\TorrentTitleParser\StringAnalyser'
        );

        $this->app->bind(
            'Fazed\TorrentTitleParser\Contracts\BlockContract',
            'Fazed\TorrentTitleParser\Models\Block'
        );
    }
}
