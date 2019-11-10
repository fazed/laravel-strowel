<?php

namespace Fazed\Strowel\Test;

use Orchestra\Testbench\TestCase as Orchestra;
use Fazed\Strowel\StrowelServiceProvider;

abstract class TestCase extends Orchestra
{
    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * {@inheritdoc}
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('strowel.block_definitions', [['(', ')'], ['[', ']'], ['{', '}']]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getPackageProviders($app)
    {
        return [StrowelServiceProvider::class];
    }
}
