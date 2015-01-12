<?php

namespace Sastrawi\POSTagger;

interface POSTaggerInterface
{
    public function tag(array $tokens);
}
