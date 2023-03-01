<?php

namespace BiffBangPow\SSMonitor\Client\Dev;

use BiffBangPow\SSMonitor\Client\Helper\EncryptionHelper;
use BiffBangPow\SSMonitor\Client\Module\AllPackageVersions;
use BiffBangPow\SSMonitor\Client\Module\CorePackageVersions;
use BiffBangPow\SSMonitor\Client\Module\SSConfiguration;
use BiffBangPow\SSMonitor\Client\Module\SystemInfo;
use SilverStripe\Dev\BuildTask;

class TestClass extends BuildTask
{

    public function run($request)
    {
        $classes = [
            CorePackageVersions::class,
            SystemInfo::class,
            SSConfiguration::class,
            AllPackageVersions::class
        ];

        $result = [];

        foreach ($classes as $class) {
            $classOut = (new $class)->getResult();
            $result = array_merge($result, $classOut);
        }

        $resultJson = serialize($result);
        $encHelper = new EncryptionHelper();
        $encData = $encHelper->encrypt($resultJson);
        $decData = $encHelper->decrypt($encData);

        echo '<div style="overflow-wrap: break-word;">';
        echo $encData;
        echo "</div><hr>";


        echo "<pre>";
        //echo $resultJson;
        //echo "\n------------------------------\n";
        print_r(unserialize($decData));

        echo "</pre>";
    }

}
