<?php

namespace BiffBangPow\SSMonitor\Client\Core;

trait ClientCommon
{

    protected $alerts = [];

    protected $warnings = [];

    /**
     * @return string
     * @throws \Exception
     */
    public function getClientName(): string
    {
        if ($this->clientName === '') {
            throw new \Exception("No client name defined for monitoring client");
        }
        return $this->clientName;
    }

    public function setAlert($alert)
    {
        $this->alerts[] = $alert;
    }

    public function setWarning($warning)
    {
        $this->warnings[] = $warning;
    }

}
