<?php

namespace Sastrawi\POSTagger\HMM;

use Sastrawi\POSTagger\Pair;

class ArrayStatsCollector implements StatsCollectorInterface
{
    private $tagCount = array();

    private $tokenTagCount = array();

    private $tagSequenceCount = array();

    public function collect(array $pairs)
    {
        foreach ($pairs as $pair) {
            if (isset($this->tagCount[$pair->getTag()])) {
                $this->tagCount[$pair->getTag()]++;
            } else {
                $this->tagCount[$pair->getTag()] = 1;
            }

            $key = $pair->getToken().'/'.$pair->getTag();

            if (isset($this->tokenTagCount[$key])) {
                $this->tokenTagCount[$key]++;
            } else {
                $this->tokenTagCount[$key] = 1;
            }
        }

        // convert to 2-grams
        $grams = array();
        for ($i = 0; $i < count($pairs) - 1; $i++) {
            $gram = array($pairs[$i]);
            for ($j = $i+1; $j < count($pairs) && $j < $i+2; $j++) {
                $gram[] = $pairs[$j];
            }

            $grams[] = $gram;
        }

        foreach ($grams as $gram) {
            $key = '';

            foreach ($gram as $i => $pair) {
                $key .= $pair->getTag();

                if ($i < count($gram) - 1) {
                    $key .= '|';
                }
            }

            if (isset($this->tagSequenceCount[$key])) {
                $this->tagSequenceCount[$key]++;
            } else {
                $this->tagSequenceCount[$key] = 1;
            }
        }
    }

    public function getTagProbability($previousTag, $currentTag)
    {
        $tagSequenceCount = isset($this->tagSequenceCount[$previousTag.'|'.$currentTag])
                                ? $this->tagSequenceCount[$previousTag.'|'.$currentTag] : 0;
        $previousTagCount = isset($this->tagCount[$previousTag]) ? $this->tagCount[$previousTag] : 0;

        if ($previousTagCount === 0) {
            return 0;
        }

        return $tagSequenceCount / $previousTagCount;
    }

    public function getTokenTagProbability($token, $tag)
    {
        $key = $token.'/'.$tag;
        $tokenTagCount = isset($this->tokenTagCount[$key]) ? $this->tokenTagCount[$key] : 0;
        $tagCount      = isset($this->tagCount[$tag]) ? $this->tagCount[$tag] : 0;

        if ($tagCount === 0) {
            return 0;
        }

        return $tokenTagCount / $tagCount;
    }

    public function getProbabilityOfTokenTagSequence(array $tokens, array $tags)
    {
        $pairs = array();

        foreach ($tokens as $i => $token) {
            $pairs[] = new Pair($token, $tags[$i]);
        }

        // convert to 2-grams
        $tagGrams = array();
        for ($i = 0; $i < count($pairs) - 1; $i++) {
            $gram = array($pairs[$i]);
            for ($j = $i+1; $j < count($pairs) && $j < $i+2; $j++) {
                $gram[] = $pairs[$j];
            }

            $tagGrams[] = $gram;
        }

        $tagProbabilities = null;
        foreach ($tagGrams as $gram) {
            if ($tagProbabilities === null) {
                $tagProbabilities = $this->getTagProbability($gram[0]->getTag(), $gram[1]->getTag());
            } else {
                $tagProbabilities *= $this->getTagProbability($gram[0]->getTag(), $gram[1]->getTag());
            }
        }

        $tokenProbabilities = null;
        foreach ($pairs as $pair) {
            if ($tokenProbabilities === null) {
                $tokenProbabilities = $this->getTokenTagProbability($pair->getToken(), $pair->getTag());
            } else {
                $tokenProbabilities *= $this->getTokenTagProbability($pair->getToken(), $pair->getTag());
            }
        }

        return $tagProbabilities * $tokenProbabilities;
    }

    public function getTagCount()
    {
        return $this->tagCount;
    }
}
