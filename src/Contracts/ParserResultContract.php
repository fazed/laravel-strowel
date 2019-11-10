<?php

namespace Fazed\Strowel\Contracts;

interface ParserResultContract
{
    /**
     * @return string
     */
    public function getSource(): string;

    /**
     * @param  string $source
     * @return $this
     */
    public function setSource(string $source): ParserResultContract;

    /**
     * @return string[]
     */
    public function getBlockData(): array;

    /**
     * @param  string[] $blockData
     * @return $this
     */
    public function setBlockData(array $blockData): ParserResultContract;

    /**
     * @return string[]
     */
    public function getRawBlockData(): array;

    /**
     * @param  string[] $rawBlockData
     * @return $this
     */
    public function setRawBlockData(array $rawBlockData): ParserResultContract;

    /**
     * @return string
     */
    public function getCleanSource(): string;

    /**
     * @param  string $cleanSource
     * @return $this
     */
    public function setCleanSource(string $cleanSource): ParserResultContract;
}
