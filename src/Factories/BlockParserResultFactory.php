<?php

namespace Fazed\Strowel\Factories;

use Fazed\Strowel\Contracts\BlockParserResultContract;
use Fazed\Strowel\Contracts\BlockParserResultFactoryContract;

class BlockParserResultFactory implements BlockParserResultFactoryContract
{
    /**
     * {@inheritdoc}
     */
    public function make($source, array $blockData, array $rawBlockData, $cleanSource)
    {
        return app(BlockParserResultContract::class, [
            'source'       => $source,
            'blockData'    => $blockData,
            'rawBlockData' => $rawBlockData,
            'cleanSource'  => $cleanSource,
        ]);
    }
}