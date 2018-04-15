<?php

namespace Fazed\TorrentTitleParser\Models;

use Fazed\TorrentTitleParser\Contracts\BlockContract;

class Block implements BlockContract
{
    /**
     * @var null|string
     */
    private $data;

    /**
     * @var null|string
     */
    private $rawData;

    /**
     * @var null|string
     */
    private $definition;

    /**
     * Block constructor.
     *
     * @param null $data
     * @param null $definition
     */
    public function __construct($data = null, $definition = null)
    {
        $this->setData($data);
        $this->setDefinition($definition);
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function setData($data)
    {
        $this->data = trim($data);
        $this->rawData = $data;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefinition($definition)
    {
        $this->definition = $definition;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRawData()
    {
        return $this->rawData;
    }

    /**
     * {@inheritdoc}
     */
    public function getRawBlock()
    {
        if (null !== $this->data && null !== $this->definition) {
            return $this->definition[0] . $this->data . $this->definition[1];
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return (string) $this->data;
    }
}
