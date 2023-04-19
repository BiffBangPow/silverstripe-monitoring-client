<?php

namespace BiffBangPow\SSMonitor\Client\Module;

use BiffBangPow\SSMonitor\Client\Core\ClientCommon;
use SilverStripe\Control\Director;
use SilverStripe\Core\Config\Configurable;
use BiffBangPow\SSMonitor\Client\Core\ClientInterface;
use SilverStripe\Core\Environment;
use SilverStripe\ORM\ArrayList;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\View\ArrayData;
use SilverStripe\View\SSViewer;

class SSConfiguration implements ClientInterface
{
    use ClientCommon;
    use Configurable;

    /**
     * @var string
     */
    private string $clientName = 'silverstripeconfig';

    /**
     * @config
     * @var string
     */
    private static $client_title = 'Silverstripe Configuration';


    public function getResult($config = null)
    {
        $admin_user = Environment::getEnv('SS_DEFAULT_ADMIN_USERNAME');
        $admin_pass = Environment::getEnv('SS_DEFAULT_ADMIN_PASSWORD');

        $res = [
            'sitename' => [
                'label' => _t(__CLASS__ . '.sitename', 'Site Name'),
                'value' => SiteConfig::current_site_config()->Title
            ],
            'envtype' => [
                'label' => _t(__CLASS__ . '.envtype', 'Environment Type'),
                'value' => Environment::getEnv('SS_ENVIRONMENT_TYPE')
            ],
            'baseurl' => [
                'label' => _t(__CLASS__ . '.baseurl', 'Base URL'),
                'value' => Director::absoluteBaseURL()
            ],
            'defaultadmin' => [
                'label' => _t(__CLASS__ . '.defaultadmin', 'Default admin set'),
                'value' => ($admin_pass || $admin_user)
            ]
        ];

        return [
            $this->getClientName() => $res
        ];
    }

    public function forTemplate()
    {
        $data = $this->getResult()[$this->getClientName()];
        $variables = ArrayList::create();

        if (isset($data['defaultadmin'])) {
            $data['defaultadmin']['value'] = ($data['defaultadmin']['value']) ? _t(__CLASS__ . '.yes', "Yes") : _t(__CLASS__ . '.no', "No");
        }

        foreach ($data as $id => $values) {
            $variables->push(ArrayData::create([
                'Variable' => $values['label'],
                'Value' => $values['value']
            ]));
        }

        $viewer = new SSViewer('BiffBangPow/SSMonitor/Client/Module/SSConfiguration');
        $html = $viewer->process(ArrayData::create([
            'Title' => $this->getClientTitle(),
            'Variables' => $variables
        ]));

        return $html;
    }

}
