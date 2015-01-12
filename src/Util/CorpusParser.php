<?php

namespace Sastrawi\POSTagger\Util;

class CorpusParser implements CorpusParserInterface
{
    private $tokenizer;

    private $stringToPair;

    public function __construct(TokenizerInterface $tokenizer, StringToPairInterface $stringToPair)
    {
        $this->tokenizer    = $tokenizer;
        $this->stringToPair = $stringToPair;
    }

    /**
     * {@inheritdoc}
     */
    public function parse($corpus)
    {
        $pairs = array();

        foreach ($this->tokenizer->tokenize($corpus) as $tokenTag) {
            $pairs[] = $this->stringToPair->fromString($tokenTag);
        }

        return $pairs;
    }
}
