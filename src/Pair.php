<?php

namespace Sastrawi\POSTagger;

class Pair implements PairInterface
{
    private $token;

    private $tag;

    public function __construct($token = '', $tag = '')
    {
        $this->setToken($token);
        $this->setTag($tag);
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setTag($tag)
    {
        $this->tag = $tag;
    }

    public function getTag()
    {
        return $this->tag;
    }
}
