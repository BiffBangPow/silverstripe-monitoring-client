<?php

namespace BiffBangPow\SSMonitor\Client\Core;

interface ClientInterface
{
    public function getResult();

    public function getClientName();

    public function getClientTitle();

    public function forTemplate();
}
