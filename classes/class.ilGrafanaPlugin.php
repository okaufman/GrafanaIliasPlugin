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
    protected $access;

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
    public function getCronJobInstance($a_job_id) : ilCronJob
    {
        $a_job_id = "\iLUB\Plugins\Grafana\Jobs\RunSync";
        return new $a_job_id();
    }

    /**
     * AfterUninstall deletes the tables from the DB
     */
    protected function afterUninstall()
    {
        $this->access = new GrafanaDBAccess();
        $this->access->removePluginTableFromDB();
    }

}
