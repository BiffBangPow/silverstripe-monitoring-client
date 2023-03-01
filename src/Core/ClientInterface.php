<?php

namespace BiffBangPow\SSMonitor\Client\Core;

interface ClientInterface
{
    public function getResult($config=null);

    public function getClientName();
}
