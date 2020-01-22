<?php

namespace iLUB\Plugins\Grafana\Helper;

/**
 * Class GrafanaDBAccess
 * This class is responsible for the interaction between the database and the plugin
 */
use ilDB;
use ilGrafanaPlugin;

class GrafanaDBAccess implements GrafanaDBInterface
{

    /**
     * @var ilDB
     */
    protected $db;

    protected $deleted_anons;
    protected $remaining_anons;
    protected $all_remaining_sessions;



    /**
     * @var DIC
     */
    protected $DIC;

    /**
     * GrafanaDBAccess constructor.
     * @param      $dic
     * @param null $db
     * @throws \Exception
     */

    public function __construct($dic_param = null, $db_param = null)
    {

        if ($dic_param == null) {
            global $DIC;
            $this->DIC = $DIC;
        } else {
            $this->DIC = $dic_param;
        }
        if ($db_param == null) {
            $this->db = $this->DIC->database();
        } else {
            $this->db = $db_param;
        }
    }

    /**
     * Removes the table from DB after uninstall is triggered.
     */
    public function removePluginTableFromDB()
    {
        $sql = "DROP TABLE " . ilGrafanaPlugin::TABLE_NAME;
        $this->db->query($sql);

        $sql = "DROP TABLE " . ilGrafanaPlugin::LOG_TABLE;
        $this->db->query($sql);
    }


    public function logsessionsToDB()
    {
        $timestamp = time();
        $this->db->insert('grafana_ses_log', array(
            'timestamp'                => array('integer', $timestamp),
            'date'                     => array('datetime', date('Y-m-d H:i:s', $timestamp)),
            'all_remaining_sessions'   => array('integer', $this->getAllSessions()),
            'active_during_last_5min'  => array('integer', $this->getSessionsBetween($timestamp - 300, $timestamp)),
            'active_during_last_15min' => array('integer', $this->getSessionsBetween($timestamp - 900, $timestamp)),
            'active_during_last_hour'  => array('integer', $hour1 = $this->getSessionsBetween($timestamp - 3600, $timestamp))
        ));
    }

    /**
     * @return mixed
     */
    public function getAllSessions()
    {
        $sql   = "SELECT count(*) FROM usr_session";
        $query = $this->db->query($sql);
        $rec   = $this->db->fetchAssoc($query);

        return $rec['count(*)'];
    }

    /**
     * @param $timeEarly
     * @param $timeLate
     * @return mixed
     */
    public function getSessionsBetween($timeEarly, $timeLate)
    {
        $sql   = "SELECT count(*) from usr_session where ctime Between '" . $timeEarly . "'and '" . $timeLate . "'";
        $query = $this->db->query($sql);
        $rec   = $this->db->fetchAssoc($query);
        return $rec['count(*)'];
    }

    public function getAllUsersWithActiveSession(){
        $sql = "SELECT count(distinct usr_session.user_id) from usr_session where FROM_UNIXTIME(expires)  > (now() + INTERVAL 11 HOUR)";
        $query = $this->db->query($sql);
        $rec   = $this->db->fetchAssoc($query);
        return $rec['count(distinct user_id)'];
    }

}