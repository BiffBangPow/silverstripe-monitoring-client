<?php

namespace BiffBangPow\SSMonitor\Client\Extension;

use SilverStripe\SiteConfig\SiteConfig;
use BiffBangPow\SSMonitor\Client\Core\ClientInterface;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Extension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\LiteralField;
use SilverStripe\View\HTML;

/**
 * Class \BiffBangPow\SSMonitor\Client\Extension\ConfigExtension
 *
 * @property SiteConfig|\BiffBangPow\SSMonitor\Client\Extension\ConfigExtension $owner
 */
class ConfigExtension extends Extension
{
    protected function updateCMSFields(FieldList $fields)
    {
        $status = $this->getStatus();
        $statusHTML = HTML::createTag('div', [], $status);

        $fields->addFieldsToTab('Root.Status', [
            LiteralField::create('statusheading',
                HTML::createTag('h1', [], _t(__CLASS__ . '.statuspage', 'System Status'))),
            LiteralField::create('stats', $statusHTML)
        ]);
    }

    private function getStatus()
    {
        $classes = ClassInfo::implementorsOf(ClientInterface::class);

        $html = '';

        foreach ($classes as $class) {
            $classInst = (new $class);
            $html .= $classInst->forTemplate();
        }

        return $html;
    }
}
