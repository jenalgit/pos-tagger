<?php

namespace Sastrawi\POSTagger\Util;

use Sastrawi\POSTagger\PairInterface;

interface PairToStringInterface
{
    public function getString(PairInterface $pair);
}
