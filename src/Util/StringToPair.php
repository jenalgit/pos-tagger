<?php

namespace Sastrawi\POSTagger\Util;

use Sastrawi\POSTagger\Pair;

class StringToPair implements StringToPairInterface
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

    public function fromString($string)
    {
        $explode = explode($this->separator, $string);
        $token   = (!empty($explode[0])) ? $explode[0] : '';
        $tag     = (!empty($explode[1])) ? $explode[1] : '';

        return new Pair($token, $tag);
    }
}
