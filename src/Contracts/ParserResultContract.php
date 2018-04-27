<?php

namespace Fazed\TorrentTitleParser\Contracts;

interface ParserResultContract
{
    /**
     * @return string
     */
    public function getSource();

    /**
     * @param  string $source
     * @return $this
     */
    public function setSource($source);

    /**
     * @return string[]
     */
    public function getBlockData();

    /**
     * @param  string[] $blockData
     * @return $this
     */
    public function setBlockData(array $blockData);

    /**
     * @return string[]
     */
    public function getRawBlockData();

    /**
     * @param  string[] $rawBlockData
     * @return $this
     */
    public function setRawBlockData(array $rawBlockData);

    /**
     * @return string
     */
    public function getCleanSource();

    /**
     * @param  string $cleanSource
     * @return $this
     */
    public function setCleanSource($cleanSource);
}