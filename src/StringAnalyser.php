<?php

namespace Fazed\TorrentTitleParser;

use Fazed\TorrentTitleParser\Models\Block;
use Fazed\TorrentTitleParser\Contracts\BlockContract;
use Fazed\TorrentTitleParser\Contracts\StringAnalyserContract;
use Fazed\TorrentTitleParser\Exceptions\InvalidBlockDefinition;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Fazed\TorrentTitleParser\Exceptions\BlockDefinitionUnbalanced;
use Fazed\TorrentTitleParser\Exceptions\BlockDefinitionExtractionError;

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
     * @var null|BlockContract[]
     */
    private $blockCache;

    /**
     * @var null|string
     */
    private $cleanStringCache;

    /**
     * StringAnalyser constructor.
     *
     * @param ConfigRepository $configRepository
     */
    public function __construct(ConfigRepository $configRepository)
    {
        $this->configRepository = $configRepository;
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
            return $this->stripBlocksFromSource();
        }

        return $this->cleanStringCache;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlocks($fresh = false)
    {
        if ($fresh || ( ! $fresh && null === $this->blockCache)) {
            return $this->extractBlocks();
        }

        return $this->blockCache;
    }

    /**
     * Analyse string and extract block data.
     *
     * @return BlockContract[]
     * @throws BlockDefinitionExtractionError
     */
    protected function extractBlocks()
    {
        $registeredBlockDefinitions = (array) $this->configRepository->get(
            'torrent-title-parser.block_definitions', []
        );

        foreach ($registeredBlockDefinitions as $blockDefinition) {
            foreach ($this->extractBlockDefinition($blockDefinition) as $block) {
                $blockStack[] = $block;
            }
        }

        return $this->blockCache = ($blockStack ?? []);
    }

    /**
     * Check whether the string contains
     * the given block definition.
     *
     * @param  string $blockDefinition
     * @return bool
     * @throws InvalidBlockDefinition
     * @throws BlockDefinitionUnbalanced
     */
    protected function sourceContainsBlockDefinition($blockDefinition)
    {
        if (\strlen($blockDefinition) !== 2) {
            throw new InvalidBlockDefinition('Definition must consist of 2 characters.');
        }

        $blockStartDefinitionCount = substr_count($this->sourceString, $blockDefinition[0]);
        $blockCloseDefinitionCount = substr_count($this->sourceString, $blockDefinition[1]);

        if ($blockStartDefinitionCount !== $blockCloseDefinitionCount) {
            throw new BlockDefinitionUnbalanced('Unbalanced definition found '. $blockDefinition .'.');
        }

        return $blockStartDefinitionCount + $blockCloseDefinitionCount >= 2;
    }

    /**
     * Extract the data of the block definition.
     *
     * @param  string $blockDefinition
     * @return BlockContract[]
     * @throws BlockDefinitionExtractionError
     */
    protected function extractBlockDefinition($blockDefinition)
    {
        try {
            if ( ! $this->sourceContainsBlockDefinition($blockDefinition)) {
                return [];
            }
        } catch (InvalidBlockDefinition $e) {
            return [];
        } catch (BlockDefinitionUnbalanced $e) {
            return [];
        }

        $hasMatches = preg_match_all(
            '/\\' . $blockDefinition[0] . '(.+?)\\' . $blockDefinition[1] .'/sui',
            $this->sourceString,
            $blockData,
            PREG_SET_ORDER
        );

        if (false === $hasMatches) {
            throw new BlockDefinitionExtractionError(
                'Unable to extract block definition "'. $blockDefinition .'"'
            );
        }

        return array_map(function ($set) use ($blockDefinition) {
            return new Block($set[1], $blockDefinition);
        }, $blockData);
    }

    /**
     * Strip the block from the source string.
     *
     * @return string
     * @throws BlockDefinitionExtractionError
     */
    protected function stripBlocksFromSource()
    {
        $string = $this->sourceString;
        $blocks = $this->getBlocks();

        foreach ($blocks as $block) {
            $blockData =  $block->getRawBlock();
            $blockStart = \strpos($string, $block->getRawBlock());
            $blockEnd = $blockStart + \strlen($block->getRawBlock());

            if ($blockStart > 0 && ctype_space($string[$blockStart - 1])) {
                $blockData = ' ' . $blockData;
            }

            if ($blockEnd < \strlen($string) && ctype_space($string[$blockEnd])) {
                $blockData .= ' ';
            }

            $string = str_replace($blockData, '', $string);
        }

        return $this->cleanStringCache = trim($string);
    }
}
