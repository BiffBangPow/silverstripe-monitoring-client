<?php

namespace BiffBangPow\SSMonitor\Client\Module;

use BiffBangPow\SSMonitor\Client\Core\ClientCommon;
use BiffBangPow\SSMonitor\Client\Core\ClientInterface;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Core\Manifest\VersionProvider;

class AllPackageVersions implements ClientInterface
{

    use ClientCommon;

    private string $clientName = 'allpackages';

    public function getResult($config=null)
    {
        $packages = [];

        /**
         * @var VersionProvider $versionProvider
         */
        $ref = new \ReflectionClass(VersionProvider::class);
        $refMethod = $ref->getMethod('getComposerLock');
        $refMethod->setAccessible(true);

        $lockContents = $refMethod->invoke(new VersionProvider());

        if (isset($lockContents['packages'])) {
            foreach ($lockContents['packages'] as $package) {
                $packageName = $package['name'];
                $version = $package['version'];
                $packages[$packageName] = $version;
            }
        }

        return [
            $this->getClientName() => $packages
        ];
    }


}
