<?php

namespace BiffBangPow\SSMonitor\Client\Control;

use BiffBangPow\SSMonitor\Client\Helper\EncryptionHelper;
use BiffBangPow\SSMonitor\Client\Module\AllPackageVersions;
use BiffBangPow\SSMonitor\Client\Module\CorePackageVersions;
use BiffBangPow\SSMonitor\Client\Module\SSConfiguration;
use BiffBangPow\SSMonitor\Client\Module\SystemInfo;
use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Environment;

/**
 * Class \BiffBangPow\SSMonitor\Client\Control\ClientController
 *
 */
class ClientController extends ContentController
{
    public function index(HTTPRequest $request)
    {
        $api_key = Environment::getEnv('MONITORING_API_KEY');
        $authKey = $request->postVar('key');
        if ((!$api_key) || ($api_key !== $authKey)) {
            return $this->httpError(404);
        }

        return $this->getReportResponse();
    }


    private function getReportResponse()
    {
        $classes = [
            CorePackageVersions::class,
            SystemInfo::class,
            SSConfiguration::class,
            AllPackageVersions::class
        ];

        $result = [
            'clientid' => Environment::getEnv('MONITORING_UUID')
        ];

        foreach ($classes as $class) {
            $classOut = (new $class)->getResult();
            $result = array_merge($result, $classOut);
        }

        $resultJson = serialize($result);
        $encHelper = new EncryptionHelper();
        return $encHelper->encrypt($resultJson);
    }
}
