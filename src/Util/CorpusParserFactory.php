<?php

namespace Sastrawi\POSTagger\Util;

class CorpusParserFactory
{
    public function createCorpusParser()
    {
        $corpusParser = new CorpusParser(new WhitespaceTokenizer(), new StringToPair());
    }
}
