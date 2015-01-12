<?php

namespace SastrawiTest\POSTagger\Util;

use Sastrawi\POSTagger\Util\CorpusParser;
use Sastrawi\POSTagger\Util\WhitespaceTokenizer;
use Sastrawi\POSTagger\Util\StringToPair;
use Sastrawi\POSTagger\Pair;

class CorpusParserTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $tokenizer    = new WhitespaceTokenizer();
        $stringToPair = new StringToPair();

        $this->parser = new CorpusParser($tokenizer, $stringToPair);
    }

    public function testParsePair()
    {
        $pair1 = new Pair('token1', 'tag1');
        $pair2 = new Pair('token2', 'tag2');
        $pair3 = new Pair('token3', 'tag3');

        $expected = array($pair1, $pair2, $pair3);

        $this->assertEquals($expected, $this->parser->parse('token1/tag1 token2/tag2 token3/tag3'));
    }
}
