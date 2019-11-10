<?php

namespace Fazed\Strowel\Contracts;

use Fazed\Strowel\Exceptions\BlockDefinitionExtractionError;

interface StringAnalyserContract
{
    /**
     * Get the string used as source.
     *
     * @return string
     */
    public function getSourceString(): string;

    /**
     * Set a string as source.
     *
     * @param  string $sourceString
     * @return $this
     */
    public function setSourceString(string $sourceString): StringAnalyserContract;

    /**
     * Get the source string without block
     * definitions found by the analyser.
     *
     * @param  bool $fresh
     * @return string
     * @throws BlockDefinitionExtractionError
     */
    public function getCleanString(bool $fresh = false): string;

    /**
     * Get the blocks analysed from the string.
     *
     * @param  bool $fresh
     * @return string[]
     * @throws BlockDefinitionExtractionError
     */
    public function getBlocks(bool $fresh = false): array;
}
