<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Utility;

use jtl\Connector\Core\IO\Path;
use jtl\Connector\Core\Utilities\Singleton;

require_once Path::combine(DIR_SYSTEM, 'library', 'db.php');
require_once Path::combine(DIR_SYSTEM, 'library', 'db', 'mysqli.php');
require_once Path::combine(DIR_SYSTEM, 'library', 'cache.php');
require_once Path::combine(DIR_SYSTEM, 'library', 'cache', 'file.php');
require_once Path::combine(DIR_SYSTEM, 'library', 'config.php');
require_once Path::combine(DIR_SYSTEM, 'library', 'language.php');
require_once Path::combine(DIR_SYSTEM, 'engine', 'event.php');
require_once Path::combine(DIR_SYSTEM, 'engine', 'controller.php');
require_once Path::combine(DIR_SYSTEM, 'engine', 'model.php');
require_once Path::combine(DIR_SYSTEM, 'engine', 'loader.php');
require_once Path::combine(DIR_SYSTEM, 'engine', 'registry.php');
require_once Path::combine(DIR_SYSTEM, 'helper', 'utf8.php');
require_once Path::combine(DIR_APPLICATION, 'controller', 'extension', 'module', 'jtl_connector.php');

class OpenCart extends Singleton
{
    private $config = null;
    private $loader = null;
    private $registry = null;
    private $directory = null;

    protected function __construct()
    {
        parent::__construct();
        /** @noinspection PhpUndefinedClassInspection */
        $this->registry = new \Registry();
        /** @noinspection PhpUndefinedClassInspection */
        $this->loader = new \Loader($this->registry);
        $this->registry->set('load', $this->loader);
        /** @noinspection PhpUndefinedClassInspection */
        $this->config = new \Config();
        $this->registry->set('config', $this->config);
        /** @noinspection PhpUndefinedClassInspection */
        $database = new \DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
        $this->registry->set('db', $database);
        /** @noinspection PhpUndefinedClassInspection */
        $cache = new \Cache('file');
        $this->registry->set('cache', $cache);
        /** @noinspection PhpUndefinedClassInspection */
        /*$event = new \Event($this->registry);
        $this->registry->set('event', $event);
        $query = $database->query('SELECT * FROM ' . DB_PREFIX . 'event');
        foreach ($query->rows as $result) {
            $event->register($result['trigger'], $result['action']);
        }*/
        $result = $database->query('SELECT directory FROM ' . DB_PREFIX . 'language WHERE code = "de"');
        if ($result->num_rows === 0 || !is_dir(DIR_CATALOG . 'language/' . $result->row['directory'])) {
            $this->directory = 'english';
        } else {
            $this->directory = $result->row['directory'];
        }
    }

    /**
     * @return OpenCart
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }


    public function loadAdminModel($model)
    {
        $file = DIR_APPLICATION . 'model/' . $model . '.php';

        return $this->loadOcModel($file, $model);
    }

    public function loadFrontendModel($model)
    {
        $file = DIR_CATALOG . 'model/' . $model . '.php';

        return $this->loadOcModel($file, $model);
    }

    private function loadOcModel($file, $model)
    {
        $class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $model);
        if (file_exists($file)) {
            include_once $file;
            $ocModel = new $class($this->registry);
            $this->registry->set('model_' . str_replace('/', '_', $model), $ocModel);
        } else {
            trigger_error('Error: Could not load model ' . $file . '!');
            exit();
        }

        return $ocModel;
    }

    public function loadToken()
    {
        return $this->getConfig(\ControllerExtensionModuleJtlConnector::CONFIG_PASSWORD_KEY);
    }

    public function getConnectorVersion()
    {
        return \ControllerExtensionModuleJtlConnector::CONNECTOR_VERSION;
    }

    public function getVersion()
    {
        return $this->getConfig(\ControllerExtensionModuleJtlConnector::CONFIG_OPENCART_VERSION);
    }

    public function getConfig($key)
    {
        $ocSetting = $this->loadAdminModel('setting/setting');

        if ($ocSetting instanceof \ModelSettingSetting) {
            return $ocSetting->getSetting(\ControllerExtensionModuleJtlConnector::CONFIG_KEY)[$key];
        }

        return '';
    }

    public function getFrontendModelString($model, $key)
    {
        $_ = [];
        include_once DIR_CATALOG . 'language/' . $this->directory . '/' . $model . '.php';

        return isset($_[$key]) ? $_[$key] : '';
    }
}