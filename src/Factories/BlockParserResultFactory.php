<?php

namespace Fazed\TorrentTitleParser\Factories;

use Fazed\TorrentTitleParser\Contracts\BlockParserResultContract;
use Fazed\TorrentTitleParser\Contracts\BlockParserResultFactoryContract;

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