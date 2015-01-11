<?php

namespace SastrawiTest\POSTagger\Util;

use Sastrawi\POSTagger\Pair;
use Sastrawi\POSTagger\Util\PairToString;

class PairToStringTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->pair    = new Pair('token', 'tag');
        $this->pairToString = new PairToString();
    }

    public function testImplementInterface()
    {
        $this->assertInstanceOf('Sastrawi\POSTagger\Util\PairToStringInterface', $this->pairToString);
    }

    public function testSetGetSeparator()
    {
        $this->assertEquals('/', $this->pairToString->getSeparator());
        $this->pairToString->setSeparator('_');
        $this->assertEquals('_', $this->pairToString->getSeparator());
    }

    public function testDefaultGetString()
    {
        $this->assertEquals('token/tag', $this->pairToString->getString($this->pair));
    }

    public function testGetStringWithCustomSeparator()
    {
        $this->pairToString->setSeparator('_');
        $this->assertEquals('token_tag', $this->pairToString->getString($this->pair));
    }
}
