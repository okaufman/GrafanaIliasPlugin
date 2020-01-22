<?php

namespace iLUB\Plugins\Grafana\UI;

use ilGrafanaConfigGUI;
use ilGrafanaPlugin;
use ilPropertyFormGUI;
use ilTextInputGUI;
use iLUB\Plugins\Grafana\Helper\cleanUpSessionsDBAccess;

/**
 * Class ConfigFOrmGUI
 * initializes GUI
 * @package iLUB\Plugins\Grafana\UI
 */
class ConfigFormGUI extends ilPropertyFormGUI
{

    /**
     * @var ilGrafanaConfigGUI
     */
    protected $parent_gui;
    /**
     * @var IGrafana
     */
    protected $config;
    /**
     * @var ilGrafanaPlugin
     */
    protected $pl;
    /**
     * @var cleanUpSessionsDBAccess
     */
    protected $access;
    /**
     * @var dic
     */
    protected $DIC;

    /**
     * ConfigFormGUI constructor.
     * @param $parent_gui
     * @param $dic
     * @throws \Exception
     */
    public function __construct($parent_gui, $dic)
    {
        $this->DIC        = $dic;
        $this->parent_gui = $parent_gui;
        $this->access     = new GrafanaDBAccess($this->DIC);
        $this->pl         = ilGrafanaPlugin::getInstance();
        $this->setFormAction($this->DIC->ctrl()->getFormAction($this->parent_gui));
        $this->initForm();
        $this->addCommandButton(ilGrafanaConfigGUI::CMD_SAVE_CONFIG, $this->pl->txt('button_save'));
        $this->addCommandButton(ilGrafanaConfigGUI::CMD_CANCEL, $this->pl->txt('button_cancel'));
        parent::__construct();
    }

    /**
     *
     */
    protected function initForm()
    {
        $this->setTitle($this->pl->txt('admin_form_title'));

        $item = new ilTextInputGUI($this->pl->txt('expiration_threshold'), ilGrafanaPlugin::EXPIRATION_THRESHOLD);
        $item->setInfo($this->pl->txt('expiration_info'));
        $item->setValue($this->access->getExpirationValue());
        $this->addItem($item);
    }
}
