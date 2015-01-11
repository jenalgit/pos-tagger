<?php

namespace Sastrawi\POSTagger\Util;

class NewLineTokenizer extends AbstractDelimiterTokenizer
{
    private static $whitespaceChars = array("\n");

    public function isDelimiter($char)
    {
        if (in_array($char, self::$whitespaceChars)) {
            return true;
        }

        return false;
    }
}
