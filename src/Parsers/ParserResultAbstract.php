<?php

namespace Fazed\Strowel\Parsers;

use Fazed\Strowel\Contracts\ParserResultContract;

abstract class ParserResultAbstract implements ParserResultContract
{
    /**
     * @var string
     */
    private $source;

    /**
     * @var string[]
     */
    private $blockData;

    /**
     * @var string[]
     */
    private $rawBlockData;

    /**
     * @var string
     */
    private $cleanSource;

    /**
     * ParserResult constructor.
     *
     * @param string   $source
     * @param string[] $blockData
     * @param string[] $rawBlockData
     * @param string   $cleanSource
     */
    protected function __construct($source, array $blockData, array $rawBlockData, $cleanSource)
    {
        $this->source = $source;
        $this->blockData = $blockData;
        $this->rawBlockData = $rawBlockData;
        $this->cleanSource = $cleanSource;
    }

    /**
     * {@inheritdoc}
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * {@inheritdoc}
     */
    public function setSource($source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockData()
    {
        return $this->blockData;
    }

    /**
     * {@inheritdoc}
     */
    public function setBlockData(array $blockData)
    {
        $this->blockData = $blockData;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRawBlockData()
    {
        return $this->rawBlockData;
    }

    /**
     * {@inheritdoc}
     */
    public function setRawBlockData(array $rawBlockData)
    {
        $this->rawBlockData = $rawBlockData;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCleanSource()
    {
        return $this->cleanSource;
    }

    /**
     * {@inheritdoc}
     */
    public function setCleanSource($cleanSource)
    {
        $this->cleanSource = $cleanSource;

        return $this;
    }
}