<?php

namespace iLUB\Plugins\Grafana\Jobs;

use ilCronJob;

/**
 * Class AbstractJob
 * @package iLUB\Plugins\Grafana\Jobs
 */
abstract class AbstractJob extends ilCronJob
{

    /**
     * @param string $message
     */
    protected function log($message)
    {
        $this->ilLog()->write($message);
    }
}
