<?php

namespace jtl\Connector\OpenCart\Utility;

use jtl\Connector\Core\IO\Path;
use jtl\Connector\Core\Utilities\Singleton;

function modification($filename)
{
    if (!defined('DIR_CATALOG')) {
        $file = DIR_MODIFICATION . 'catalog/' . substr($filename, strlen(DIR_APPLICATION));
    } else {
        $file = DIR_MODIFICATION . 'admin/' . substr($filename, strlen(DIR_APPLICATION));
    }

    if (substr($filename, 0, strlen(DIR_SYSTEM)) == DIR_SYSTEM) {
        $file = DIR_MODIFICATION . 'system/' . substr($filename, strlen(DIR_SYSTEM));
    }

    if (is_file($file)) {
        return $file;
    }

    return $filename;
}

require_once(modification(DIR_SYSTEM . 'library/db.php'));
require_once(modification(DIR_SYSTEM . 'library/db/mysqli.php'));
require_once(modification(DIR_SYSTEM . 'library/cache.php'));
require_once(modification(DIR_SYSTEM . 'library/cache/file.php'));
require_once(modification(DIR_SYSTEM . 'library/config.php'));
require_once(modification(DIR_SYSTEM . 'engine/event.php'));
require_once(modification(DIR_SYSTEM . 'engine/controller.php'));
require_once(modification(DIR_SYSTEM . 'engine/model.php'));
require_once(modification(DIR_SYSTEM . 'engine/loader.php'));
require_once(modification(DIR_SYSTEM . 'engine/registry.php'));
require_once(modification(DIR_SYSTEM . 'helper/utf8.php'));
require_once(Path::combine(DIR_APPLICATION, 'controller', 'module', 'jtlconnector.php'));

class OpenCart extends Singleton
{
    private $config = null;
    private $loader = null;
    private $registry = null;

    protected function __construct()
    {
        parent::__construct();
        $this->registry = new \Registry();
        $this->config = new \Config();
        $this->registry->set('config', $this->config);
        $database = new \DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        $this->registry->set('db', $database);
        $this->loader = new \Loader($this->registry);
        // Cache
        $cache = new \Cache('file');
        $this->registry->set('cache', $cache);
        // Event
        $event = new \Event($this->registry);
        $this->registry->set('event', $event);
        $query = $database->query("SELECT * FROM " . DB_PREFIX . "EVENT");
        foreach ($query->rows as $result) {
            $event->register($result['trigger'], $result['action']);
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
            include_once($file);
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
        return $this->getConfig(\ControllerModuleJtlconnector::CONFIG_PASSWORD_KEY);
    }

    public function getVersion()
    {
        return $this->getConfig(\ControllerModuleJtlconnector::CONFIG_OPENCART_VERSION);
    }

    public function getConfig($key)
    {
        $settings = $this->loadAdminModel('setting/setting');
        return $settings->getSetting(\ControllerModuleJtlconnector::CONFIG_KEY)[$key];
    }
}