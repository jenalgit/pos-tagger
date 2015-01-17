<?php

namespace Sastrawi\POSTagger\Util;

class NGram
{
    public static function arrayToGram(array $array, $n = 2)
    {
        $grams = array();
        for ($i = 0; $i < count($array) - 1; $i++) {
            $gram = array($array[$i]);
            for ($j = $i+$n-1; $j < count($array) && $j < $i+$n; $j++) {
                $gram[] = $array[$j];
            }

            $grams[] = $gram;
        }

        return $grams;
    }
}
