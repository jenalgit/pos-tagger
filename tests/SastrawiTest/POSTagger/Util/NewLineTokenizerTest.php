<?php

namespace SastrawiTest\POSTagger\Util;

use Sastrawi\POSTagger\Util\NewLineTokenizer;

class NewLineTokenizerTest extends \PHPUnit_Framework_TestCase
{
    public function testImplements()
    {
        $this->assertInstanceOf(
            'Sastrawi\POSTagger\Util\TokenizerInterface',
            new NewLineTokenizer()
        );
    }

    public function testTokenize()
    {
        $tokenizer = new NewLineTokenizer();

        $expected = array(
            'Token1/tag1',
            'token2/tag2',
        );

        $this->assertEquals($expected, $tokenizer->tokenize("Token1/tag1\ntoken2/tag2"));
    }

    public function testTokenizeIgnoreDoubleNewLine()
    {
        $tokenizer = new NewLineTokenizer();

        $expected = array(
            'Token1/tag1',
            'token2/tag2',
        );

        $this->assertEquals($expected, $tokenizer->tokenize("Token1/tag1\n\n\ntoken2/tag2"));
    }
}
