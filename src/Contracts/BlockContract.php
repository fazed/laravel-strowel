<?php

namespace Fazed\TorrentTitleParser\Contracts;

interface BlockContract
{
    /**
     * @return null|string
     */
    public function getData();

    /**
     * @param  null|string $data
     * @return $this
     */
    public function setData($data);

    /**
     * @return null|string
     */
    public function getDefinition();

    /**
     * @param  null|string $definition
     * @return $this
     */
    public function setDefinition($definition);

    /**
     * @return null|string
     */
    public function getRawData();

    /**
     * @return null|string
     */
    public function getRawBlock();

    /**
     * @return string
     */
    public function __toString();
}
