<?php

namespace Fazed\Strowel\Contracts;

interface BlockParserContract
{
    /**
     * Parse the block definition from the source.
     *
     * @param  string $source
     * @param  string[] $blockDefinition
     * @return BlockParserResultFactoryContract
     */
    public function parse($source, array $blockDefinition);
}