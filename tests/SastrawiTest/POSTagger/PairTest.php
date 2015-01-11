<?php

namespace SastrawiTest\POSTagger;

use Sastrawi\POSTagger\Pair;

class PairTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructPreservedTokenAndTag()
    {
        $pair = new Pair('token', 'tag');
        $this->assertEquals('token', $pair->getToken());
        $this->assertEquals('tag', $pair->getTag());
    }

    public function testDefaultEmptyTokenAndTag()
    {
        $pair = new Pair();
        $this->assertEquals('', $pair->getToken());
        $this->assertEquals('', $pair->getTag());
    }

    public function testSetGetToken()
    {
        $pair = new Pair();
        $pair->setToken('token1');
        $this->assertEquals('token1', $pair->getToken());
    }

    public function testSetGetTag()
    {
        $pair = new Pair();
        $pair->setTag('tag1');
        $this->assertEquals('tag1', $pair->getTag());
    }
}
