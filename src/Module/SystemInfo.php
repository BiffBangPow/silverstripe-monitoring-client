<?php

namespace BiffBangPow\SSMonitor\Client\Module;

use BiffBangPow\SSMonitor\Client\Core\ClientCommon;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Environment;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DB;
use SilverStripe\View\ArrayData;
use SilverStripe\View\SSViewer;

class SystemInfo implements \BiffBangPow\SSMonitor\Client\Core\ClientInterface
{
    use ClientCommon;
    use Configurable;

    /**
     * @var string
     */
    private string $clientName = 'systeminfo';

    /**
     * @config
     * @var string
     */
    private static $client_title = 'System info';

    /**
     * @config
     * @var array
     */
    private static $env_variables = [];


    public function getResult($config = null)
    {
        $conn = DB::get_conn();
        // Assumes database class is like "MySQLDatabase" or "MSSQLDatabase" (eg. suffixed with "Database")
        $dbType = substr(strrchr(get_class($conn), '\\'), 1, -8);
        $dbVersion = $conn->getVersion();
        $databaseName = $conn->getSelectedDatabase();

        $info = [
            'php' => [
                'label' => _t(__CLASS__ . '.phpversion', 'PHP version'),
                'value' => phpversion()
            ],
            'hostip' => [
                'label' => _t(__CLASS__ . '.hostip', 'IP address'),
                'value' => $_SERVER['SERVER_ADDR']
            ],
            'memorylimit' => [
                'label' => _t(__CLASS__ . '.memorylimit', 'Memory limit'),
                'value' => ini_get('memory_limit')
            ],
            'uploadlimit' => [
                'label' => _t(__CLASS__ . '.uploadlimit', 'Upload limit'),
                'value' => ini_get('upload_max_filesize')
            ],
            'maxscripttime' => [
                'label' => _t(__CLASS__ . '.scripttime', 'Script execution time limit'),
                'value' => ini_get('max_execution_time')
            ],
            'dbengine' => [
                'label' => _t(__CLASS__ . '.dbengine', 'Database engine'),
                'value' => $dbType
            ],
            'dbversion' => [
                'label' => _t(__CLASS__ . '.dbversion', 'Database version'),
                'value' => $dbVersion
            ],
            'databasename' => [
                'label' => _t(__CLASS__ . '.dbname', 'Database name'),
                'value' => $databaseName
            ]
        ];

        $envVars = $this->config()->get('env_variables');
        $env = [];
        foreach ($envVars as $envVar) {
            $envData = Environment::getEnv($envVar);
            $value = ($envData) ? $envData : _t(__CLASS__ . '.EnvNotSet', 'Not set');
            $env[$envVar] = $value;
        }

        if (count($env) > 0) {
            $info['environment'] = [
                'label' => _t(__CLASS__ . '.environment', 'Environment variables'),
                'value' => $env
            ];
        }

        return [
            $this->getClientName() => $info
        ];
    }

    /**
     *
     * @return \SilverStripe\ORM\FieldType\DBHTMLText
     * @throws \Exception
     */
    public function forTemplate()
    {
        $data = $this->getResult()[$this->getClientName()];
        $variables = ArrayList::create();

        $env = (isset($data['environment'])) ? $data['environment'] : false;
        if ($env) {
            unset($data['environment']);
            $envVariables = ArrayList::create();
            foreach ($env['value'] as $variableName => $value) {
                $envVariables->push(ArrayData::create([
                    'Variable' => $variableName,
                    'Value' => $value
                ]));
            }
        }

        foreach ($data as $id => $values) {
            $variables->push(ArrayData::create([
                'Variable' => $values['label'],
                'Value' => $values['value']
            ]));
        }

        $viewer = new SSViewer('BiffBangPow/SSMonitor/Client/Module/SystemInfo');
        $html = $viewer->process(ArrayData::create([
            'Title' => $this->getClientTitle(),
            'Variables' => $variables,
            'Environment' => $envVariables
        ]));

        return $html;
    }
}
