<?php

namespace Fazed\Strowel\Parsers;

use Fazed\Strowel\Contracts\BlockParserContract;
use Fazed\Strowel\Contracts\BlockParserResultFactoryContract;
use Fazed\Strowel\Contracts\ParserResultContract;

class BlockParser implements BlockParserContract
{
    /**
     * @var string[]
     */
    protected $blockStack = [];

    /**
     * @var string[]
     */
    protected $bufferStack = [0 => ''];

    /**
     * @var null|int
     */
    protected $currentStackDepth = 0;

    /**
     * @var array
     */
    protected $delimiterStack = [];

    /**
     * @var array
     */
    protected $blockDefinitions = [];

    /**
     * @var BlockParserResultFactoryContract
     */
    private $parserResultFactory;

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
    public function parse(string $source, array $blockDefinitions): ParserResultContract
    {
        $this->blockDefinitions = $blockDefinitions;

        $startDelimiters = array_column($blockDefinitions, 0);
        $endDelimiters = array_column($blockDefinitions, 1);

        for ($i = 0, $iMax = \strlen($source); $i < $iMax; $i++) {
            if (\in_array($source[$i], $startDelimiters, false)) {
                $this->incrementStackDepth()
                    ->setActiveDelimiterSet($source[$i]);
            }

            $this->pushCharToStack($source[$i]);

            if (\in_array($source[$i], $endDelimiters, false)
                && null !== $this->getCurrentDelimiterSet()
                && $this->getCurrentDelimiterSet()[1] === $source[$i]
            ) {
                $this->popCurrentStack()
                    ->popCurrentDelimiterStack()
                    ->decrementStackDepth();
            }
        }

        $this->blockStack = array_unique($this->blockStack);

        return $this->makeResultInstance($source);
    }

    /**
     * Push a character to the currently active stack.
     *
     * @param  string $character
     * @return $this
     */
    protected function pushCharToStack(string $character): BlockParserContract
    {
        $this->bufferStack[$this->currentStackDepth] .= $character;

        return $this;
    }

    /**
     * Set the active delimiter set by the given (start) delimiter.
     *
     * @param  string $delimiter
     * @return $this
     */
    protected function setActiveDelimiterSet(string $delimiter): BlockParserContract
    {
        $this->delimiterStack[$this->currentStackDepth] = $this->getDelimiterSetByStartDelimiter($delimiter);

        return $this;
    }

    /**
     * Get the stack which is currently active.
     *
     * @return null|string
     */
    protected function getCurrentStack(): ?string
    {
        return $this->bufferStack[$this->currentStackDepth] ?? null;
    }

    /**
     * Get the delimiter set which is currently active.
     *
     * @return null|string[]
     */
    protected function getCurrentDelimiterSet(): ?array
    {
        return $this->delimiterStack[$this->currentStackDepth] ?? null;
    }

    /**
     * Move the current stack to the block stack.
     *
     * @return $this
     */
    protected function popCurrentStack(): BlockParserContract
    {
        $this->blockStack[] = array_pop($this->bufferStack);

        return $this;
    }

    /**
     * Remove the last item from the delimiter stack.
     *
     * @return $this
     */
    protected function popCurrentDelimiterStack(): BlockParserContract
    {
        array_pop($this->delimiterStack);

        return $this;
    }

    /**
     * Move the stack depth down one step.
     *
     * @return $this
     */
    protected function incrementStackDepth(): BlockParserContract
    {
        ++$this->currentStackDepth;

        return $this->initializeStackDepth();
    }

    /**
     * Move the stack depth up one step.
     *
     * @return $this
     */
    protected function decrementStackDepth(): BlockParserContract
    {
        --$this->currentStackDepth;

        return $this->initializeStackDepth();
    }

    /**
     * Initialize an empty string on the current stack depth.
     *
     * @return $this
     */
    protected function initializeStackDepth(): BlockParserContract
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
     * @return string[]
     */
    protected function getDelimiterSetByStartDelimiter(string $character): array
    {
        if (false !== ($idx = array_search($character, array_column($this->blockDefinitions, 0), false))) {
            return $this->blockDefinitions[$idx];
        }

        return [];
    }

    /**
     * Create a new parse result instance.
     *
     * @param  string $sourceString
     * @return ParserResultContract
     */
    protected function makeResultInstance(string $sourceString): ParserResultContract
    {
        $rawBlockData = array_values(
            array_map(static function ($block) {
                return $block[0] . trim(substr($block, 1, -1)) . $block[\strlen($block) - 1];
            }, $this->blockStack)
        );

        $blockData = array_values(
            array_map(static function ($block) {
                return trim(substr($block, 1, -1));
            }, $this->blockStack)
        );

        $cleanString = trim(\count($this->blockStack) ? $this->bufferStack[0] ?? '' : $sourceString);

        return $this->parserResultFactory->make($sourceString, $blockData, $rawBlockData, $cleanString);
    }
}
