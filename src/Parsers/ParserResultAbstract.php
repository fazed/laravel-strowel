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
    protected function __construct(
        string $source,
        array $blockData,
        array $rawBlockData,
        string $cleanSource
    )
    {
        $this->source = $source;
        $this->blockData = $blockData;
        $this->rawBlockData = $rawBlockData;
        $this->cleanSource = $cleanSource;
    }

    /**
     * {@inheritdoc}
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * {@inheritdoc}
     */
    public function setSource(string $source): ParserResultContract
    {
        $this->source = $source;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockData(): array
    {
        return $this->blockData;
    }

    /**
     * {@inheritdoc}
     */
    public function setBlockData(array $blockData): ParserResultContract
    {
        $this->blockData = $blockData;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRawBlockData(): array
    {
        return $this->rawBlockData;
    }

    /**
     * {@inheritdoc}
     */
    public function setRawBlockData(array $rawBlockData): ParserResultContract
    {
        $this->rawBlockData = $rawBlockData;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCleanSource(): string
    {
        return $this->cleanSource;
    }

    /**
     * {@inheritdoc}
     */
    public function setCleanSource(string $cleanSource): ParserResultContract
    {
        $this->cleanSource = $cleanSource;

        return $this;
    }
}
