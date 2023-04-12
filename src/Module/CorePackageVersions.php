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
    use Configurable;

    private string $clientName = 'corepackages';

    private static $included_modules;

    public function getResult($config = null)
    {
        $modules = $this->config()->get('included_modules');

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
