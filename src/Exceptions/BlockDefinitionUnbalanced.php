<?php

namespace Fazed\Strowel\Exceptions;

class BlockDefinitionUnbalanced extends ExceptionAbstract
{
    /**
     * @var string
     */
    const DEFAULT_MESSAGE = 'Definition must consist of 2 characters.';

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
        parent::__construct($message);
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
