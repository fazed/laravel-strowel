<?php

namespace Fazed\TorrentTitleParser\Exceptions;

class BlockDefinitionExtractionError extends ExceptionAbstract
{
    /**
     * @var string
     */
    const DEFAULT_MESSAGE = 'Unable to extract block definition "%s".';

    /**
     * @var string
     */
    private $definition;

    /**
     * BlockDefinitionExtractionError constructor.
     *
     * @param string $definition
     * @param string $message
     */
    public function __construct($definition, $message = self::DEFAULT_MESSAGE)
    {
        parent::__construct(sprintf($message, str_replace(['%', '$'], ['\\%', '\\$'], $definition)));
        $this->definition = $definition;
    }

    /**
     * @return string
     */
    public function getDefinition()
    {
        return $this->definition;
    }
}
