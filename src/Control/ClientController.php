<?php

namespace BiffBangPow\SSMonitor\Client\Control;

use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Environment;

class ClientController extends ContentController
{
    public function index(HTTPRequest $request)
    {
        $api_key = Environment::getEnv('MONITORING_API_KEY');
        $headerKey = $request->postVar('key');
        if ((!$api_key) || ($api_key !== $headerKey)) {
            return $this->httpError(404);
        }
    }
}
