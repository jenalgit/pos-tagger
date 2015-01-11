<?php

namespace Sastrawi\POSTagger\Util;

class WhitespaceTokenizer extends AbstractDelimiterTokenizer
{
    private static $whitespaceChars = array(' ');

    public function isDelimiter($char)
    {
        if (in_array($char, self::$whitespaceChars)) {
            return true;
        }

        return false;
    }
}
