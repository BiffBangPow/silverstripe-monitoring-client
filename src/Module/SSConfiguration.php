<?php

namespace BiffBangPow\SSMonitor\Client\Module;

use BiffBangPow\SSMonitor\Client\Core\ClientCommon;
use SilverStripe\Control\Director;
use SilverStripe\Core\Config\Configurable;
use BiffBangPow\SSMonitor\Client\Core\ClientInterface;
use SilverStripe\Core\Environment;
use SilverStripe\SiteConfig\SiteConfig;

class SSConfiguration implements ClientInterface
{
    use ClientCommon;

    private string $clientName = 'silverstripeconfig';


    public function getResult($config = null)
    {
        $res = [
            'Site Name' => SiteConfig::current_site_config()->Title,
            'Environment Type' => Environment::getEnv('SS_ENVIRONMENT_TYPE'),
            'Base URL' => Director::absoluteBaseURL()
        ];

        $admin_user = Environment::getEnv('SS_DEFAULT_ADMIN_USERNAME');
        $admin_pass = Environment::getEnv('SS_DEFAULT_ADMIN_PASSWORD');

        $res['Default Admin'] = ($admin_pass || $admin_user) ? "Set" : "Not Set";

        return [
            $this->getClientName() => $res
        ];
    }

}
