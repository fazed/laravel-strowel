<?php

namespace Fazed\Strowel\Factories;

use Fazed\Strowel\Contracts\BlockParserResultContract;
use Fazed\Strowel\Contracts\BlockParserResultFactoryContract;
use Fazed\Strowel\Contracts\ParserResultContract;
use Illuminate\Contracts\Container\Container;

class BlockParserResultFactory implements BlockParserResultFactoryContract
{
    /**
     * @var Container
     */
    private $container;

    /**
     * BlockParserResultFactory constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function make(
        string $source,
        array $blockData,
        array $rawBlockData,
        string $cleanSource
    ): ParserResultContract
    {
        return $this->container->make(BlockParserResultContract::class, [
            'source'       => $source,
            'blockData'    => $blockData,
            'rawBlockData' => $rawBlockData,
            'cleanSource'  => $cleanSource,
        ]);
    }
}
