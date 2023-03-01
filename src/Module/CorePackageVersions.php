<?php

namespace BiffBangPow\SSMonitor\Client\Module;

use BiffBangPow\SSMonitor\Client\Core\ClientCommon;
use BiffBangPow\SSMonitor\Client\Core\ClientInterface;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Core\Manifest\VersionProvider;

class CorePackageVersions implements ClientInterface
{

    use ClientCommon;

    private string $clientName = 'corepackages';

    public function getResult($config = null)
    {
        // TODO:  Deal with configuration options

        $modules = [
            'silverstripe/framework',
            'silverstripe/cms',
            'dnadesign/silverstripe-elemental'
        ];

        /**
         * @var VersionProvider $versionProvider
         */
        $versionProvider = Injector::inst()->create(VersionProvider::class);
        $packages = $versionProvider->getModuleVersionFromComposer($modules);

        return [
            $this->getClientName() => $packages
        ];

    }


}
