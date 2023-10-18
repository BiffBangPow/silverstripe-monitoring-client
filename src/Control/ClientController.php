<?php

namespace BiffBangPow\SSMonitor\Client\Control;

use BiffBangPow\SSMonitor\Client\Core\ClientInterface;
use BiffBangPow\SSMonitor\Client\Helper\EncryptionHelper;
use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\Control\Director;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\Util\IPUtils;
use SilverStripe\Core\ClassInfo;
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
            return (Director::isDev())
                ? $this->httpError(400, _t(__CLASS__ . '.IncorrectAPIKey', 'API key not recognised'))
                : $this->httpError(404);
        }

        $validIP = Environment::getEnv('MONITORING_VALID_IP');
        if ($validIP) {
            $requestIP = $request->getIP();
            if (!IPUtils::checkIP($requestIP, $validIP)) {
                return (Director::isDev())
                    ? $this->httpError(400, _t(__CLASS__ . '.InvalidIP', 'Invalid request IP ({requestip})', [
                        'requestip' => $requestIP
                    ]))
                    : $this->httpError(404);
            }
        }


        return $this->getReportResponse();
    }


    private function getReportResponse()
    {
        $classes = ClassInfo::implementorsOf(ClientInterface::class);

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
