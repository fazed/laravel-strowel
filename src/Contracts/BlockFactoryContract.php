<?php

namespace Fazed\TorrentTitleParser\Contracts;

interface BlockFactoryContract
{
    /**
     * Create a new BlockContract instance.
     *
     * @param  string $data
     * @param  string $definition
     * @return BlockContract
     */
    public function make($data, $definition);
}