<?php

namespace SastrawiTest\POSTagger;

use Sastrawi\POSTagger\HMMPOSTagger;
use Sastrawi\POSTagger\HMM\ArrayStatsCollector;
use Sastrawi\POSTagger\Util\CorpusParser;
use Sastrawi\POSTagger\Util\WhitespaceTokenizer;
use Sastrawi\POSTagger\Util\StringToPair;

class HMMPOSTaggerTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $corpusParser   = new CorpusParser(new WhitespaceTokenizer(), new StringToPair());
        $statsCollector = new ArrayStatsCollector();
        $this->tagger   = new HMMPOSTagger($corpusParser, $statsCollector);
    }

    public function testGetTagProbability()
    {
        $this->tagger->train('A/B C/D C/D A/D A/B ./.');

        $this->assertEquals(0, $this->tagger->getTagProbability('Z', 'Z'));
        // count(previous_tag, current_tag) / count(previous_tag)
        // count D and D happens 2 times, D happens 3 times so 2/3
        $this->assertEquals(2/3, $this->tagger->getTagProbability('D', 'D'));
        $this->assertEquals(1, $this->tagger->getTagProbability('START', 'B'));
        $this->assertEquals(0.5, $this->tagger->getTagProbability('B', 'D'));
        $this->assertEquals(0, $this->tagger->getTagProbability('.', 'D'));
    }

    public function testGetTokenTagProbability()
    {
        $this->tagger->train('A/B C/D C/D A/D A/B ./.');

        // maximum likelihood estimate
        // count (word and tag) / count(tag)
        $this->assertEquals(0, $this->tagger->getTokenTagProbability('Z', 'Z'));

        // A and B happens 2 times, count of B happens twice therefore 100%
        $this->assertEquals(1, $this->tagger->getTokenTagProbability('A', 'B'));

        // A and D happens 1 time, count of D happens 3 times so 1/3
        $this->assertEquals(1/3, $this->tagger->getTokenTagProbability('A', 'D'));

        // START and START happens 1 time, count of START happens 1 time, so 1
        $this->assertEquals(1, $this->tagger->getTokenTagProbability('START', 'START'));

        $this->assertEquals(1, $this->tagger->getTokenTagProbability('.', '.'));
    }

    /**
     * @depends testGetTagProbability
     * @depends testGetTokenTagProbability
     */
    public function testGetProbabilityOfTokenTagSequence()
    {
        $this->tagger->train('A/B C/D A/D A/B ./.');

        $tokens = array('START', 'A', 'C', 'A', 'A', '.');
        $tags   = array('START', 'B', 'D', 'D', 'B', '.');

        $tagProbabilities = array_reduce(array(
            $this->tagger->getTagProbability('B', 'D'),
            $this->tagger->getTagProbability('D', 'D'),
            $this->tagger->getTagProbability('D', 'B'),
            $this->tagger->getTagProbability('B', '.'),
        ), function ($carry, $item) {

            if ($carry === null) {
                $carry = $item;
            } else {
                $carry *= $item;
            }

            return $carry;
        });

        $tokenProbabilities = array_reduce(array(
            $this->tagger->getTokenTagProbability('A', 'B'),
            $this->tagger->getTokenTagProbability('C', 'D'),
            $this->tagger->getTokenTagProbability('A', 'D'),
            $this->tagger->getTokenTagProbability('A', 'B'),
        ), function ($carry, $item) {
            if ($carry === null) {
                $carry = $item;
            } else {
                $carry *= $item;
            }

            return $carry;
        });

        $expected = $tokenProbabilities * $tagProbabilities;
        $this->assertSame($expected, $this->tagger->getProbabilityOfTokenTagSequence($tokens, $tags));
    }

    public function testTag()
    {
        $this->tagger->train('I/PRO want/V to/TO race/V ./. I/PRO like/V cats/N ./.');

        $tokens       = array('START', 'I', 'want', 'to', 'race', '.');
        $expectedTags = array('START', 'PRO', 'V', 'TO', 'V', '.');

        $this->assertEquals($expectedTags, $this->tagger->tag($tokens));
    }
}
