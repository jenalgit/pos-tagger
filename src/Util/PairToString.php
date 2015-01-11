<?php

namespace Sastrawi\POSTagger\Util;

use Sastrawi\POSTagger\PairInterface;

class PairToString implements PairToStringInterface
{
    private $separator = '/';

    public function setSeparator($separator)
    {
        $this->separator = $separator;
    }

    public function getSeparator()
    {
        return $this->separator;
    }

    public function getString(PairInterface $pair)
    {
        return $pair->getToken().$this->getSeparator().$pair->getTag();
    }
}
