<?php

namespace BiffBangPow\SSMonitor\Client\Extension;

use BiffBangPow\SSMonitor\Client\Module\AllPackageVersions;
use BiffBangPow\SSMonitor\Client\Module\CorePackageVersions;
use BiffBangPow\SSMonitor\Client\Module\SSConfiguration;
use BiffBangPow\SSMonitor\Client\Module\SystemInfo;
use SilverStripe\Core\Extension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\LiteralField;
use SilverStripe\View\HTML;

/**
 * Class \BiffBangPow\SSMonitor\Client\Extension\ConfigExtension
 *
 * @property \SilverStripe\SiteConfig\SiteConfig|\BiffBangPow\SSMonitor\Client\Extension\ConfigExtension $owner
 */
class ConfigExtension extends Extension
{
    public function updateCMSFields(FieldList $fields)
    {
        $status = $this->getStatus();
        $statusHTML = HTML::createTag('pre', [], print_r($status, true));


        $fields->addFieldsToTab('Root.Status', [
            LiteralField::create('statusheading',
                HTML::createTag('h1', [], _t(__CLASS__ . '.statuspage', 'System Status'))),
            LiteralField::create('stats', $statusHTML)
        ]);
    }

    private function getStatus()
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

        return $result;
    }
}
