<?php

namespace Sastrawi\POSTagger;

use Sastrawi\POSTagger\Util\CorpusParserInterface;
use Sastrawi\POSTagger\HMM\StatsCollectorInterface;

class HMMPOSTagger implements TrainablePOSTaggerInterface
{
    private $corpusParser;

    private $collector;

    public function __construct(CorpusParserInterface $corpusParser, StatsCollectorInterface $collector)
    {
        $this->corpusParser = $corpusParser;
        $this->collector    = $collector;
    }

    public function train($corpus)
    {
        $pairs = $this->corpusParser->parse($corpus);

        if (!empty($pairs)) {
            array_unshift($pairs, new Pair('START', 'START'));
        }

        $this->collector->collect($pairs);
    }

    public function getTagProbability($previousTag, $currentTag)
    {
        return $this->collector->getTagProbability($previousTag, $currentTag);
    }

    public function getTokenTagProbability($token, $tag)
    {
        return $this->collector->getTokenTagProbability($token, $tag);
    }

    public function getProbabilityOfTokenTagSequence(array $tokens, array $tags)
    {
        return $this->collector->getProbabilityOfTokenTagSequence($tokens, $tags);
    }

    public function tag(array $tokens)
    {
        $tags = array();

        $lastViterbi = array();

        foreach ($tokens as $i => $token) {
            $viterbi = array();
            $bestTag = null;

            if ($token === 'START') {
                $tags[] = 'START';
                continue;
            }

            foreach ($this->collector->getTagCount() as $tag => $count) {
                if ($tag === 'START') {
                    continue;
                }

                $lastViterbiProbs = array();
                foreach ($lastViterbi as $t => $p) {
                    $prob = $p * $this->getTagProbability($t, $tag) * $this->getTokenTagProbability($token, $tag);
                    $lastViterbiProbs[$t] = $prob;
                }

                if (empty($lastViterbi)) {
                    $probability = $this->getTagProbability('START', $tag)
                                        * $this->getTokenTagProbability($token, $tag);
                } else {
                    $bestPreviousTag = array_search(max($lastViterbiProbs), $lastViterbiProbs);
                    $probability = $lastViterbi[$bestPreviousTag]
                                        * $this->getTagProbability($bestPreviousTag, $tag)
                                        * $this->getTokenTagProbability($token, $tag);
                }

                if ($probability > 0) {
                    $viterbi[$tag] = $probability;
                }
            }

            $lastViterbi = $viterbi;

            if (!empty($viterbi)) {
                $bestTag = array_search(max($viterbi), $viterbi);
            }

            if (empty($bestTag) || empty($viterbi) || $viterbi[$bestTag] === 0) {
                $bestTag = array_search(max($this->collector->getTagCount()), $this->collector->getTagCount());
            }

            $tags[] = $bestTag;
        }

        return $tags;
    }
}
