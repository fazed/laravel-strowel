<?php

namespace Fazed\TorrentTitleParser\Parsers;

use Fazed\TorrentTitleParser\Contracts\BlockParserContract;
use Fazed\TorrentTitleParser\Contracts\BlockParserResultFactoryContract;

class BlockParser implements BlockParserContract
{
    /**
     * @var string[]
     */
    protected $blockStack = [];

    /**
     * @var string[]
     */
    protected $bufferStack = [];

    /**
     * @var string[]
     */
    protected $delimiterStack = [];

    /**
     * @var null|int
     */
    protected $currentStackDepth;

    /**
     * @var BlockParserResultFactoryContract
     */
    private $parserResultFactory;

    /**
     * @var string[]
     */
    private $blockDefinitions;

    /**
     * BlockParser constructor.
     *
     * @param BlockParserResultFactoryContract $parserResultFactory
     */
    public function __construct(BlockParserResultFactoryContract $parserResultFactory)
    {
        $this->parserResultFactory = $parserResultFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function parse($source, array $blockDefinitions)
    {
        $this->blockDefinitions = $blockDefinitions;

        $startDelimiters = array_column($blockDefinitions, 0);
        $endDelimiters = array_column($blockDefinitions, 1);

        for ($i = 0, $iMax = \strlen($source); $i < $iMax; $i++) {
            if (\in_array($source[$i], $startDelimiters)) {
                $this->incrementStackDepth()
                    ->setActiveDelimiterSet($source[$i]);
            }

            $this->pushCharToStack($source[$i]);

            if (\in_array($source[$i], $endDelimiters) && $this->getCurrentDelimiterSet()[1] === $source[$i]) {
                $this->popCurrentStack()
                    ->popCurrentDelimiterStack()
                    ->decrementStackDepth();
            }
        }

        $this->blockStack = array_unique($this->blockStack);

        return $this->parserResultFactory->make(
            $source,
            array_map(function ($block) {
                return trim(substr($block, 1, -1));
            }, $this->blockStack),
            array_map(function ($block) {
                return $block[0] . trim(substr($block, 1, -1)) . $block[\strlen($block) - 1];
            }, $this->blockStack),
            trim($this->bufferStack[0] ?? '')
        );
    }

    /**
     * Push a character to the currently active stack.
     *
     * @param  string $character
     * @return $this
     */
    protected function pushCharToStack($character)
    {
        if (null !== $this->currentStackDepth) {
            $this->bufferStack[$this->currentStackDepth] .= $character;
        }

        return $this;
    }

    /**
     * Set the active delimiter set by the given (start) delimiter.
     *
     * @param  string $delimiter
     * @return $this
     */
    protected function setActiveDelimiterSet($delimiter)
    {
        $this->delimiterStack[$this->currentStackDepth] = $this->getDelimiterSetByStartDelimiter($delimiter);

        return $this;
    }

    /**
     * Get the stack which is currently active.
     *
     * @return null|string
     */
    protected function getCurrentStack()
    {
        if (null === $this->currentStackDepth) {
            return null;
        }

        return $this->bufferStack[$this->currentStackDepth];
    }

    /**
     * Get the delimiter set which is currently active.
     *
     * @return null|string
     */
    protected function getCurrentDelimiterSet()
    {
        if (null === $this->currentStackDepth) {
            return null;
        }

        return $this->delimiterStack[$this->currentStackDepth];
    }

    /**
     * Move the current stack to the block stack.
     *
     * @return $this
     */
    protected function popCurrentStack()
    {
        $this->blockStack[] = array_pop($this->bufferStack);

        return $this;
    }

    /**
     * Remove the last item from the delimiter stack.
     *
     * @return $this
     */
    protected function popCurrentDelimiterStack()
    {
        array_pop($this->delimiterStack);

        return $this;
    }

    /**
     * Move the stack depth down one step.
     *
     * @return $this
     */
    protected function incrementStackDepth()
    {
        ++$this->currentStackDepth;

        return $this->initializeStackDepth();
    }

    /**
     * Move the stack depth up one step.
     *
     * @return $this
     */
    protected function decrementStackDepth()
    {
        --$this->currentStackDepth;

        return $this->initializeStackDepth();
    }

    /**
     * Initialize an empty string on the current stack depth.
     *
     * @return $this
     */
    protected function initializeStackDepth()
    {
        if ( ! array_key_exists($this->currentStackDepth, $this->bufferStack)) {
            $this->bufferStack[$this->currentStackDepth] = '';
        }

        return $this;
    }

    /**
     * Get the delimiter stack containing the given start character.
     *
     * @param  string $character
     * @return string
     */
    protected function getDelimiterSetByStartDelimiter($character)
    {
        if (false !== ($idx = array_search($character, array_column($this->blockDefinitions, 0)))) {
            return $this->blockDefinitions[$idx];
        }

        return '';
    }
}