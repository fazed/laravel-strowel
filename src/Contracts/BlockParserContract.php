<?php

namespace Fazed\Strowel\Contracts;

interface BlockParserContract
{
    /**
     * Parse the block definition from the source.
     *
     * @param  string $source
     * @param  string[] $blockDefinition
     * @return ParserResultContract
     */
    public function parse(string $source, array $blockDefinition): ParserResultContract;
}
