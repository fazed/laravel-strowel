<?php

namespace Fazed\TorrentTitleParser\Test;

use Fazed\TorrentTitleParser\Contracts\StringAnalyserContract;

class StringAnalyserTest extends TestCase
{
    const STRING_WITH_BLOCKS = '[FFF] Shokugeki no Souma S3 - 11 [1080p][BE0D72E6].mkv';
    const STRING_WITHOUT_BLOCKS = 'Shokugeki no Souma S3 - 11 .mkv';
    const STRING_WITH_UNBALANCED_BLOCKS = '[FFF) Shokugeki no Souma S3 - 11 [1080p][BE0D72E6].mkv';
    const STRING_WITH_INDISTINCT_BLOCKS = '[FFF][FFF] Shokugeki no Souma S3 - 11 [1080p][BE0D72E6].mkv';
    const STRING_WITH_RECURSIVE_BLOCKS = '(Recursive (test(test2)) [FFF {1}]) Shokugeki no Souma S3 - 11 [1080p][BE0D72E6].mkv';

    /** @test */
    public function it_can_analyse_string_w_recursive_blocks()
    {
        $blocks = app(StringAnalyserContract::class)
            ->setSourceString(static::STRING_WITH_RECURSIVE_BLOCKS)
            ->getBlocks();

        $this->assertCount(7, $blocks);
    }

    /** @test */
    public function it_can_analyse_string_wo_blocks()
    {
        $blocks = app(StringAnalyserContract::class)
            ->setSourceString(static::STRING_WITHOUT_BLOCKS)
            ->getBlocks();

        $this->assertCount(0, $blocks);
    }

    /** @test */
    public function it_can_analyse_string_w_blocks()
    {
        $blocks = app(StringAnalyserContract::class)
            ->setSourceString(static::STRING_WITH_BLOCKS)
            ->getBlocks();

        $this->assertCount(3, $blocks);

        $this->assertArraySubset(
            ['FFF', '1080p', 'BE0D72E6'],
            $blocks
        );
    }

    /** @test */
    public function it_can_clean_source_string()
    {
        $cleanString = app(StringAnalyserContract::class)
            ->setSourceString(static::STRING_WITH_BLOCKS)
            ->getCleanString();

        $this->assertSame(static::STRING_WITHOUT_BLOCKS, $cleanString);
    }

    /** @test */
    public function it_can_ignore_unbalanced_blocks()
    {
        /** @var StringAnalyserContract $analyser */
        $analyser = app(StringAnalyserContract::class)
            ->setSourceString(static::STRING_WITH_UNBALANCED_BLOCKS);

        $this->assertCount(2, $analyser->getBlocks());

        $this->assertArraySubset(['1080p', 'BE0D72E6'], $analyser->getBlocks());
    }

    public function it_can_filter_distinct_blocks()
    {
        /** @var StringAnalyserContract $analyser */
        $analyser = app(StringAnalyserContract::class)
            ->setSourceString(static::STRING_WITH_UNBALANCED_BLOCKS);

        $this->assertCount(4, $analyser->getDistinctBlocks());

        $this->assertArraySubset(
            ['FFF', '1080p', 'BE0D72E6', 'test'],
            $analyser->getBlocks()
        );

        $this->assertSame(static::STRING_WITHOUT_BLOCKS, $analyser->getCleanString());
    }
}
