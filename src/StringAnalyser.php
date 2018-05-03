<?php

namespace Fazed\TorrentTitleParser;

use Fazed\TorrentTitleParser\Contracts\BlockParserContract;
use Fazed\TorrentTitleParser\Contracts\ParserResultContract;
use Fazed\TorrentTitleParser\Contracts\StringAnalyserContract;
use Fazed\TorrentTitleParser\Exceptions\InvalidBlockDelimiter;
use Fazed\TorrentTitleParser\Exceptions\InvalidBlockDefinition;
use Fazed\TorrentTitleParser\Exceptions\BlockDefinitionUnbalanced;

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
            config('torrent-title-parser.block_definitions', []), function ($definitionSet) {
                try { $this->validateBlockDefinitionDelimiters($definitionSet); return true; }
                catch (\Exception $e) { return false; }
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceString()
    {
        return $this->sourceString;
    }

    /**
     * {@inheritdoc}
     */
    public function setSourceString($sourceString)
    {
        $this->sourceString = trim($sourceString);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCleanString($fresh = false)
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
    public function getBlocks($fresh = false)
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
    protected function extractBlocks($string)
    {
        $this->parserResultCache = app(BlockParserContract::class)
            ->parse($string, $this->blockDefinitions);

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
    protected function validateBlockDefinitionDelimiters($definition)
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
    protected function validateBlockDefinitionDelimiter($delimiter)
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
     * @throws BlockDefinitionUnbalanced
     */
    protected function validateBlockBalance($source, $blockDefinition)
    {
        if (substr_count($source, $blockDefinition[0]) !== substr_count($source, $blockDefinition[1])) {
            throw new BlockDefinitionUnbalanced($blockDefinition);
        }
    }
}
