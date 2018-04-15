<?php

namespace Fazed\TorrentTitleParser\Test;

use Orchestra\Testbench\TestCase as Orchestra;
use Fazed\TorrentTitleParser\TorrentTitleParserProvider;

abstract class TestCase extends Orchestra
{
    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * {@inheritdoc}
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('torrent-title-parser.block_definitions', ['[]', '()', '{}']);
        $app['config']->set('torrent-title-parser.real_title_regex', null);
    }

    /**
     * {@inheritdoc}
     */
    protected function getPackageProviders($app)
    {
        return [TorrentTitleParserProvider::class];
    }
}
