<?php

namespace Fazed\Strowel\Parsers;

class BlockParserResult extends ParserResultAbstract
{
    /**
     * BlockParserResult constructor.
     *
     * @param string   $source
     * @param string[] $blockData
     * @param string[] $rawBlockData
     * @param string   $cleanSource
     */
    public function __construct($source, array $blockData, array $rawBlockData, $cleanSource)
    {
        parent::__construct($source, $blockData, $rawBlockData, $cleanSource);
    }
}