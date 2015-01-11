<?php

namespace SastrawiTest\POSTagger\Util;

use Sastrawi\POSTagger\Pair;
use Sastrawi\POSTagger\Util\StringToPair;

class StringToPairTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->stringToPair = new StringToPair();
    }

    public function testImplementInterface()
    {
        $this->assertInstanceOf('Sastrawi\POSTagger\Util\StringToPairInterface', $this->stringToPair);
    }

    public function testSetGetSeparator()
    {
        $this->assertEquals('/', $this->stringToPair->getSeparator());
        $this->stringToPair->setSeparator('_');
        $this->assertEquals('_', $this->stringToPair->getSeparator());
    }

    public function testDefaultFromString()
    {
        $this->assertEquals(new Pair('token', 'tag'), $this->stringToPair->fromString('token/tag'));
    }

    public function testFromStringWithCustomSeparator()
    {
        $this->stringToPair->setSeparator('_');
        $this->assertEquals(new Pair('token', 'tag'), $this->stringToPair->fromString('token_tag'));
    }
}
