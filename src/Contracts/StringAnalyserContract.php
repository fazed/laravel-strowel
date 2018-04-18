<?php

namespace Fazed\TorrentTitleParser\Contracts;

use Fazed\TorrentTitleParser\Exceptions\BlockDefinitionExtractionError;

interface StringAnalyserContract
{
    /**
     * Get the string used as source.
     *
     * @return string
     */
    public function getSourceString();

    /**
     * Set a string as source.
     *
     * @param  string $sourceString
     * @return $this
     */
    public function setSourceString($sourceString);

    /**
     * Get the source string without block
     * definitions found by the analyser.
     *
     * @param  bool $fresh
     * @return string
     * @throws BlockDefinitionExtractionError
     */
    public function getCleanString($fresh = false);

    /**
     * Get the blocks analysed from the string.
     *
     * @param  bool $fresh
     * @return BlockContract[]
     * @throws BlockDefinitionExtractionError
     */
    public function getBlocks($fresh = false);

    /**
     * Get all distinct blocks from the string.
     *
     * @return BlockContract[]
     * @throws BlockDefinitionExtractionError
     */
    public function getDistinctBlocks();
}
