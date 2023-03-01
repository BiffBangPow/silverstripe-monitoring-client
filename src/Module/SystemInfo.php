<?php

namespace BiffBangPow\SSMonitor\Client\Module;

use BiffBangPow\SSMonitor\Client\Core\ClientCommon;
use SilverStripe\ORM\DB;

class SystemInfo implements \BiffBangPow\SSMonitor\Client\Core\ClientInterface
{
    use ClientCommon;

    private string $clientName = 'systeminfo';

    public function getResult($config = null)
    {
        $conn = DB::get_conn();
        // Assumes database class is like "MySQLDatabase" or "MSSQLDatabase" (suffixed with "Database")
        $dbType = substr(strrchr(get_class($conn), '\\'), 1, -8);
        $dbVersion = $conn->getVersion();
        $databaseName = $conn->getSelectedDatabase();

        $info = [
            'PHP' => phpversion(),
            'Memory Limit' => ini_get('memory_limit'),
            'Upload Limit' => ini_get('upload_max_filesize'),
            'Script Time' => ini_get('max_execution_time'),
            'Database Engine' => $dbType,
            'Database Version' => $dbVersion,
            'Database Name' => $databaseName
        ];

        return [
            $this->getClientName() => $info
        ];
    }
}
