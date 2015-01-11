<?php

namespace SastrawiTest\POSTagger\Util;

use Sastrawi\POSTagger\Util\WhitespaceTokenizer;

class WhitespaceTokenizerTest extends \PHPUnit_Framework_TestCase
{
    public function testImplements()
    {
        $this->assertInstanceOf(
            'Sastrawi\POSTagger\Util\TokenizerInterface',
            new WhitespaceTokenizer()
        );
    }

    public function testTokenize()
    {
        $tokenizer = new WhitespaceTokenizer();

        $expected = array(
            'Token1/tag1',
            'token2/tag2',
            'token3/tag3',
            './.',
        );

        $this->assertEquals($expected, $tokenizer->tokenize("Token1/tag1 token2/tag2 token3/tag3 ./."));
    }

    public function testTokenizeIgnoreDoubleWhitespace()
    {
        $tokenizer = new WhitespaceTokenizer();

        $expected = array(
            'Token1/tag1',
            'token2/tag2',
            'token3/tag3',
            './.',
        );

        $this->assertEquals($expected, $tokenizer->tokenize("Token1/tag1   token2/tag2  token3/tag3 ./."));
    }
}
