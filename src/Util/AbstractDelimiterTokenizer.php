<?php

namespace Sastrawi\POSTagger\Util;

abstract class AbstractDelimiterTokenizer implements TokenizerInterface
{
    abstract public function isDelimiter($char);

    public function tokenize($string)
    {
        $tokens = array();
        $buffer = '';

        for ($i = 0; $i < strlen($string); $i++) {
            if ($this->isDelimiter($string[$i])) {
                if (!empty($buffer)) {
                    $tokens[] = $buffer;
                    $buffer   = '';
                }
            } else {
                $buffer .= $string[$i];
            }
        }

        if (!empty($buffer)) {
            $tokens[] = $buffer;
            $buffer   = '';
        }

        return $tokens;
    }
}
