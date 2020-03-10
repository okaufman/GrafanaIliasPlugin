<?php

require_once __DIR__ . "/../vendor/autoload.php";

use iLUB\Plugins\Grafana\Helper\GrafanaDBAccess;
use iLUB\Plugins\Grafana\Jobs\RunSync;


/**
 * Class ilGrafanaPlugin
 * @package
 */
class ilGrafanaPlugin extends ilCronHookPlugin
{

    const PLUGIN_ID = 'grafana';
    const PLUGIN_NAME = 'Grafana';
    const TABLE_NAME = 'grafana_config';
    const SES_LOG_TABLE = 'grafana_ses_log';


    /**
     * @var ilGrafanaPlugin
     */
    protected static $instance;
    /**
     * @var $this ->access
     */
    protected $db_access;

    /**
     * @return string
     */
    public function getPluginName() : string
    {
        return self::PLUGIN_NAME;
    }

    /**
     * @return ilGrafanaPlugin
     */
    public static function getInstance() : ilGrafanaPlugin
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @return ilCronJob[]
     */
    public function getCronJobInstances() : array
    {

        return [new RunSync()];
    }

    /**
     * @param string $a_job_id
     * @return ilCronJob
     */
    public function getCronJobInstance($runSync) : ilCronJob
    {
        return  new RunSync();
    }

    /**
     * AfterUninstall deletes the tables from the DB
     */
    protected function afterUninstall()
    {
        $this->db_access = new GrafanaDBAccess();
        $this->db_access->removePluginTableFromDB();
    }

}
