<?php

class ControllerModuleJtlconnector extends Controller
{
    const CONNECTOR_VERSION = '0.5.1';
    const CONFIG_KEY = 'connector';
    const CONFIG_PASSWORD_KEY = 'connector_password';
    const CONFIG_ATTRIBUTE_GROUP = 'connector_attribute_group';
    const CONFIG_OPENCART_VERSION = 'connector_opencart_version';

    private $error = [];

    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->load->model('setting/setting');
        $this->load->model('catalog/attribute_group');
    }

    public function index()
    {
        $this->load->language('module/jtlconnector');

        $this->document->setTitle($this->language->get('heading_title'));

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            // If an input field is required the actions are done here

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'],
                'SSL'));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_info'] = $this->language->get('text_info');
        $data['text_requirements'] = $this->language->get('text_requirements');
        $data['text_write_access'] = $this->language->get('text_write_access');
        $data['text_url'] = $this->language->get('text_url');
        $data['text_password'] = $this->language->get('text_password');
        $data['text_version'] = $this->language->get('text_version');
        $data['text_php_version'] = $this->language->get('text_php_version');

        $data['url'] = HTTP_CATALOG . 'jtlconnector/';
        $data['version'] = self::CONNECTOR_VERSION;
        $data['php_version'] = version_compare(PHP_VERSION, '5.4', '>=');
        $data['zipping'] = class_exists('ZipArchive');
        $data['write_access'] = $this->writeAccess();

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('module/jtlconnector', 'token=' . $this->session->data['token'], 'SSL')
        ];

        $data['action'] = $this->url->link('module/jtlconnector', 'token=' . $this->session->data['token'], 'SSL');

        $data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->request->post[self::CONFIG_PASSWORD_KEY])) {
            $data[self::CONFIG_PASSWORD_KEY] = $this->request->post[self::CONFIG_PASSWORD_KEY];
        } else {
            $data[self::CONFIG_PASSWORD_KEY] = $this->model_setting_setting->getSetting(self::CONFIG_KEY)[self::CONFIG_PASSWORD_KEY];
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('module/jtlconnector.tpl', $data));
    }

    private function writeAccess()
    {
        $configPath = DIR_CATALOG . '../jtlconnector/config/config.json';
        $logsPath = DIR_CATALOG . '../jtlconnector/logs';
        $imagePath = DIR_IMAGE . 'catalog/';
        return [
            'jtlconnector/config/config.json' => is_file($configPath) && is_writable($configPath),
            'jtlconnector/logs/' => is_dir($logsPath) && is_writable($logsPath),
            'image/catalog/' => is_dir($imagePath) && is_writable($imagePath)
        ];
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'module/jtlconnector')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function install()
    {
        $this->activateLinking();
        $this->activateChecksum();
        $this->activateFilter();
        // TODO: unit, delivery note, payment

        $result = $this->db->query('SELECT * FROM oc_language');
        $groupDescriptions = [];
        foreach ($result->rows as $row) {
            if ($row['code'] === 'de') {
                $groupDescriptions[$row['language_id']] = ['name' => 'Produkt Eigenschaften'];
            } else {
                $groupDescriptions[$row['language_id']] = ['name' => 'Product Specifications'];
            }
        }
        $groupId = $this->model_catalog_attribute_group->addAttributeGroup([
            'sort_order' => 0,
            'attribute_group_description' => $groupDescriptions
        ]);
        $password = $this->createPassword();

        $this->model_setting_setting->editSetting(self::CONFIG_KEY, [
            self::CONFIG_PASSWORD_KEY => $password,
            self::CONFIG_ATTRIBUTE_GROUP => $groupId,
            self::CONFIG_OPENCART_VERSION => VERSION
        ]);
    }

    private function activateFilter()
    {
        $filterInstalled = 'SELECT * FROM ' . DB_PREFIX . 'extension WHERE type="module" AND CODE="filter"';
        if (empty($this->db->query($filterInstalled)->rows)) {
            $this->db->query('INSERT INTO ' . DB_PREFIX . 'extension (type, CODE) VALUES ("module", "filter")');
        }
        $filterActivated = 'SELECT * FROM ' . DB_PREFIX . 'setting WHERE `key`="filter_stats"';
        if (empty($this->db->query($filterActivated)->rows)) {
            $this->db->query('INSERT INTO ' . DB_PREFIX . 'setting SET store_id = 0, `code` = "filter", `key` = "filter_status", `value` = "1"');
        } else {
            $this->db->query('UPDATE ' . DB_PREFIX . 'setting SET `value` = "1" WHERE `key` = "filter_status"');
        }
        $filterInLayout = 'SELECT * FROM ' . DB_PREFIX . 'layout_module WHERE layout_id = 3 AND CODE="filter"';
        if (empty($this->db->query($filterInLayout)->rows)) {
            $this->db->query('
                INSERT INTO ' . DB_PREFIX . 'layout_module (layout_id, CODE, position, sort_order)
                VALUES (3, "filter", "column_left", 1)'
            );
        }
    }

    private function activateLinking()
    {
        $linkQuery = "
            CREATE TABLE IF NOT EXISTS jtl_connector_link (
                endpointId CHAR(64) NOT NULL,
                hostId INT(10) NOT NULL,
                type INT(10),
                PRIMARY KEY (endpointId, hostId, type)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
        $this->db->query($linkQuery);
    }

    private function activateChecksum()
    {
        $checksumQuery = "
            CREATE TABLE IF NOT EXISTS jtl_connector_checksum (
                endpointId INT(10) UNSIGNED NOT NULL,
                type TINYINT UNSIGNED NOT NULL,
                checksum VARCHAR(255) NOT NULL,
                PRIMARY KEY (endpointId)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
        $this->db->query($checksumQuery);
    }

    private function createPassword()
    {
        if (function_exists('com_create_guid') === true) {
            return trim(com_create_guid(), '{}');
        }
        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535),
            mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }


    public function uninstall()
    {
        $this->db->query('DROP TABLE IF EXISTS jtl_connector_link');
        $this->db->query('DROP TABLE IF EXISTS jtl_connector_checksum');
        $configs = $this->model_setting_setting->getSetting(self::CONFIG_KEY);
        $this->model_catalog_attribute_group->deleteAttributeGroup($configs[self::CONFIG_ATTRIBUTE_GROUP]);
        $this->model_setting_setting->deleteSetting(self::CONFIG_KEY);
    }
}
