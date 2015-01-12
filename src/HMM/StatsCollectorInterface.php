<?php

namespace Sastrawi\POSTagger\HMM;

interface StatsCollectorInterface
{
    public function collect(array $pairs);
}
