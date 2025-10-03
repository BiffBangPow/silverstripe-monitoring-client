<?php

namespace BiffBangPow\SSMonitor\Client\Module;

use SilverStripe\Model\List\ArrayList;
use SilverStripe\Model\ArrayData;
use SilverStripe\ORM\FieldType\DBHTMLText;
use Exception;
use BiffBangPow\SSMonitor\Client\Core\ClientCommon;
use BiffBangPow\SSMonitor\Client\Core\ClientInterface;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Core\Manifest\VersionProvider;
use SilverStripe\View\SSViewer;

class CorePackageVersions implements ClientInterface
{
    use ClientCommon;
    use Configurable;

    private string $clientName = 'corepackages';

    private static $client_title = 'Core software packages';

    private static $included_modules;

    public function getResult()
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

    /**
     *
     * @return DBHTMLText
     * @throws Exception
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

        $viewer = new SSViewer('BiffBangPow/SSMonitor/Client/Module/CorePackageVersions');
        $html = $viewer->process(ArrayData::create([
            'Title' => $this->getClientTitle(),
            'Packages' => $versions
        ]));

        return $html;
    }

}
