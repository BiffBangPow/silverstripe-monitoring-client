<?php

namespace BiffBangPow\SSMonitor\Client\Module;

use BiffBangPow\SSMonitor\Client\Core\ClientCommon;
use BiffBangPow\SSMonitor\Client\Core\ClientInterface;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Core\Manifest\VersionProvider;
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;
use SilverStripe\View\SSViewer;

class AllPackageVersions implements ClientInterface
{

    use ClientCommon;
    use Configurable;

    /**
     * @config
     * @var string
     */
    private static $client_title = 'All installed vendor packages';

    /**
     * @var string
     */
    private string $clientName = 'allpackages';

    public function getResult()
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

    /**
     *
     * @return \SilverStripe\ORM\FieldType\DBHTMLText
     * @throws \Exception
     */
    public function forTemplate()
    {
        $data = $this->getResult();
        $versions = ArrayList::create();

        foreach ($data[$this->getClientName()] as $packageName => $version) {
            $versions->push([
                'PackageName' => $packageName,
                'PackageVersion' => $version
            ]);
        }

        $viewer = new SSViewer('BiffBangPow/SSMonitor/Client/Module/AllPackageVersions');
        $html = $viewer->process(ArrayData::create([
            'Title' => $this->getClientTitle(),
            'Packages' => $versions
        ]));

        return $html;
    }

}
