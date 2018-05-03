<?php

namespace Fazed\Strowel\Exceptions;

abstract class ExceptionAbstract extends \Exception
{
    /**
     * @var string
     */
    const DEFAULT_MESSAGE = '';

    /**
     * ExceptionAbstract constructor.
     *
     * @param null|string $message
     */
    public function __construct($message = self::DEFAULT_MESSAGE)
    {
        parent::__construct($message, 0, null);
    }
}