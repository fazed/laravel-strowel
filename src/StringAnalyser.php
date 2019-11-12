<?php

namespace Fazed\Strowel;

use Fazed\Strowel\Contracts\BlockParserContract;
use Fazed\Strowel\Contracts\ParserResultContract;
use Fazed\Strowel\Contracts\StringAnalyserContract;
use Fazed\Strowel\Exceptions\BlockDefinitionUnbalanced;
use Fazed\Strowel\Exceptions\InvalidBlockDefinition;
use Fazed\Strowel\Exceptions\InvalidBlockDelimiter;
use Illuminate\Support\Facades\Config;

class StringAnalyser implements StringAnalyserContract
{
    /**
     * The base string which will gets analysed.
     *
     * @var string
     */
    private $sourceString;

    /**
     * A list of block definitions incl. delimiters.
     *
     * @var array
     */
    private $blockDefinitions;

    /**
     * A list of previously found blocks on the instance.
     *
     * @var null|string[]
     */
    private $blockCache;

    /**
     * A representation of the source string without blocks.
     *
     * @var null|string
     */
    private $cleanStringCache;

    /**
     * The previously parser result on the instance.
     *
     * @var null|ParserResultContract
     */
    private $parserResultCache;

    /**
     * StringAnalyser constructor.
     */
    public function __construct()
    {
        $this->blockDefinitions = array_filter(
            Config::get('strowel.block_definitions', []), function ($definitionSet) {
                try { $this->validateBlockDefinitionDelimiters($definitionSet); return true; }
                catch (\Exception $e) { return false; }
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceString(): string
    {
        return $this->sourceString;
    }

    /**
     * {@inheritdoc}
     */
    public function setSourceString(string $sourceString): StringAnalyserContract
    {
        $this->sourceString = trim($sourceString);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCleanString(bool $fresh = false): string
    {
        if ($fresh || ( ! $fresh && null === $this->cleanStringCache)) {
            if (null === $this->parserResultCache) {
                $this->getBlocks();
            }

            return $this->cleanStringCache = $this->parserResultCache->getCleanSource();
        }

        return $this->cleanStringCache;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlocks(bool $fresh = false): array
    {
        if ($fresh || ( ! $fresh && null === $this->blockCache)) {
            return $this->blockCache = $this->extractBlocks($this->sourceString);
        }

        return $this->blockCache;
    }

    /**
     * Analyse string and extract block data.
     *
     * @param  string $string
     * @return string[]
     */
    protected function extractBlocks(string $string): array
    {
        $this->parserResultCache = app(
            BlockParserContract::class
        )->parse(
            $string, $this->blockDefinitions
        );

        return $this->parserResultCache->getBlockData();
    }

    /**
     * Validate the given block definition delimiters.
     *
     * @param  string[] $definition
     * @return void
     * @throws InvalidBlockDelimiter
     * @throws InvalidBlockDefinition
     */
    protected function validateBlockDefinitionDelimiters(array $definition): void
    {
        if (\count($definition) !== 2) {
            throw new InvalidBlockDefinition($definition);
        }

        foreach ($definition as $delimiter) {
            $this->validateBlockDefinitionDelimiter($delimiter);
        }
    }

    /**
     * Validate a single block definition delimiter.
     *
     * @param  string $delimiter
     * @return void
     * @throws InvalidBlockDelimiter
     */
    protected function validateBlockDefinitionDelimiter(string $delimiter): void
    {
        if (\strlen($delimiter) !== 1) {
            throw new InvalidBlockDelimiter('Block delimiter can only consist of 1 character.');
        }
    }

    /**
     * Validate whether the block(s) contained in the string are balanced.
     *
     * @param  string $source
     * @param  string[] $blockDefinition
     * @return void
     * @throws BlockDefinitionUnbalanced
     */
    protected function validateBlockBalance(string $source, array $blockDefinition): void
    {
        if (substr_count($source, $blockDefinition[0]) !== substr_count($source, $blockDefinition[1])) {
            throw new BlockDefinitionUnbalanced($blockDefinition);
        }
    }
}
