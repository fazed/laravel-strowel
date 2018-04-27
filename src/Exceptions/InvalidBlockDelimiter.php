<?php

namespace Fazed\TorrentTitleParser\Exceptions;

class InvalidBlockDelimiter extends ExceptionAbstract
{
    /**
     * @var string
     */
    const DEFAULT_MESSAGE = 'Invalid block delimiter(s) "%s"';

    /**
     * @var string
     */
    private $delimiter;

    public function __construct($delimiter, $message = self::DEFAULT_MESSAGE)
    {
        parent::__construct(sprintf($message, $delimiter));
        $this->delimiter = $delimiter;
    }

    /**
     * @return string
     */
    public function getDelimiter()
    {
        return $this->delimiter;
    }
}