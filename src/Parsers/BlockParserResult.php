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
    public function __construct(
        string $source,
        array $blockData,
        array $rawBlockData,
        string $cleanSource
    )
    {
        parent::__construct($source, $blockData, $rawBlockData, $cleanSource);
    }
}
