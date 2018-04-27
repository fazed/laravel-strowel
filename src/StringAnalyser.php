<?php

namespace Fazed\TorrentTitleParser;

use Fazed\TorrentTitleParser\Contracts\BlockContract;
use Fazed\TorrentTitleParser\Contracts\ParserResultContract;
use Fazed\TorrentTitleParser\Contracts\StringAnalyserContract;
use Fazed\TorrentTitleParser\Exceptions\InvalidBlockDefinition;
use Fazed\TorrentTitleParser\Exceptions\InvalidBlockDelimiter;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
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
     * @var ConfigRepository
     */
    private $configRepository;

    /**
     * @var array
     */
    private $blockDefinitions;

    /**
     * @var null|string[]
     */
    private $blockCache;

    /**
     * @var null|string
     */
    private $cleanStringCache;

    /**
     * @var null|ParserResultContract
     */
    private $parserResultCache;

    /**
     * StringAnalyser constructor.
     *
     * @param ConfigRepository $configRepository
     */
    public function __construct(ConfigRepository $configRepository)
    {
        $this->configRepository = $configRepository;

        $this->blockDefinitions = array_filter(
            (array) $this->configRepository->get('torrent-title-parser.block_definitions', []),
            function ($definitionSet) {
                try {
                    $this->validateBlockDefinitionDelimiters($definitionSet);
                } catch (\Exception $e) {
                    return false;
                }

                return true;
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
     * {@inheritdoc}
     */
    public function getDistinctBlocks()
    {
        return array_unique($this->getBlocks());
    }

    /**
     * Analyse string and extract block data.
     *
     * @param  string $string
     * @return BlockContract[]
     */
    protected function extractBlocks($string)
    {
        /** @var \Fazed\TorrentTitleParser\Contracts\BlockParserContract $parser */
        $parser = app('Fazed\TorrentTitleParser\Contracts\BlockParserContract');

        $this->parserResultCache = $parser->parse($string, $this->blockDefinitions);

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
