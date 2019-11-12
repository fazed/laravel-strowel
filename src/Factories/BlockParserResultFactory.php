<?php

namespace Fazed\Strowel\Factories;

use Fazed\Strowel\Contracts\BlockParserResultContract;
use Fazed\Strowel\Contracts\BlockParserResultFactoryContract;
use Fazed\Strowel\Contracts\ParserResultContract;

class BlockParserResultFactory implements BlockParserResultFactoryContract
{
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
        return app(BlockParserResultContract::class, [
            'source'       => $source,
            'blockData'    => $blockData,
            'rawBlockData' => $rawBlockData,
            'cleanSource'  => $cleanSource,
        ]);
    }
}
