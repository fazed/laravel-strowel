<?php

namespace Fazed\TorrentTitleParser\Factories;

use Fazed\TorrentTitleParser\Contracts\BlockContract;
use Fazed\TorrentTitleParser\Contracts\BlockFactoryContract;

class BlockFactory implements BlockFactoryContract
{
    /**
     * {@inheritdoc}
     */
    public function make($data, $definition)
    {
        return app(BlockContract::class, ['data' => $data, 'definitinon' => $definition]);
    }
}