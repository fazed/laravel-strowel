<?php

namespace Fazed\TorrentTitleParser\Exceptions;

class InvalidBlockDefinition extends ExceptionAbstract
{
    /**
     * @var string
     */
    const DEFAULT_MESSAGE = 'Unbalanced definition "%s".';

    /**
     * @var string
     */
    private $definition;

    /**
     * ExceptionAbstract constructor.
     *
     * @param string $definition
     * @param null|string $message
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
