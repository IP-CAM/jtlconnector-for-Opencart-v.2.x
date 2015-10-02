<?php

class ControllerModuleJtlconnector extends Controller
{
    const SEPARATOR = '_';
    const CONNECTOR_VERSION = '0.2.0';
    const CONFIG_KEY = 'connector';
    const CONFIG_PASSWORD_KEY = self::CONFIG_KEY . self::SEPARATOR . 'password';
    const CONFIG_ATTRIBUTE_GROUP = self::CONFIG_KEY . self::SEPARATOR . 'attribute_group';

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
            $this->model_setting_setting->editSettingValue(self::CONFIG_KEY, self::CONFIG_PASSWORD_KEY,
                $this->request->post[self::CONFIG_PASSWORD_KEY]);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'],
                'SSL'));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_info'] = $this->language->get('text_info');
        $data['text_url'] = $this->language->get('text_url');
        $data['text_password'] = $this->language->get('text_password');
        $data['text_version'] = $this->language->get('text_version');

        $data['url'] = HTTP_CATALOG . 'jtlconnector/';
        $data['version'] = self::CONNECTOR_VERSION;

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

    private function phpVersion()
    {
        return array((version_compare(PHP_VERSION, '5.4') >= 0), array(PHP_VERSION));
    }

    private function gdlib()
    {
        return array((extension_loaded('gd') && function_exists('gd_info')));
    }

    private function configFile()
    {
        $path = CONNECTOR_DIR . '/config';
        if (file_exists($path . '/config.json')) {
            $path = $path . '/config.json';
        }

        return array(is_writable($path), array($path));
    }

    private function connectorLog()
    {
        $path = CONNECTOR_DIR . '/logs';

        return array(is_writable($path), array($path));
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

        $this->model_setting_setting->editSetting(self::CONFIG_KEY, [
            self::CONFIG_PASSWORD_KEY => '',
            self::CONFIG_ATTRIBUTE_GROUP => $groupId
        ]);
    }

    public function uninstall()
    {
        $this->db->query('DROP TABLE jtl_connector_link');
        $configs = $this->model_setting_setting->getSetting(self::CONFIG_KEY);
        $this->model_catalog_attribute_group->deleteAttributeGroup($configs[self::CONFIG_ATTRIBUTE_GROUP]);
        $this->model_setting_setting->deleteSetting(self::CONFIG_KEY);
    }

    private function activateFilter()
    {
        $filterActivated = 'SELECT * FROM ' . DB_PREFIX . 'extension WHERE type="module" AND code="filter"';
        if (empty($this->db->query($filterActivated)->rows)) {
            $this->db->query('INSERT INTO ' . DB_PREFIX . 'extension (type, code) VALUES ("module", "filter")');
            $this->db->query('
                INSERT INTO ' . DB_PREFIX . 'setting (code, key, value, serialized, store_id)
                VALUES ("filter", "filter_status", "1", 0, 0)'
            );
        }
        $filterInLayout = 'SELECT * FROM ' . DB_PREFIX . 'layout_module WHERE layout_id = 3 AND code="filter"';
        if (empty($this->db->query($filterInLayout)->rows)) {
            $this->db->query('
                INSERT INTO ' . DB_PREFIX . 'layout_module (layout_id, code, position, sort_order)
                VALUES (3, "filter", "column_left", 1)'
            );
        }
    }

    private function activateLinking()
    {
        $linkQuery = "
            CREATE TABLE IF NOT EXISTS jtl_connector_link (
                endpointId char(64) NOT NULL,
                hostId int(10) NOT NULL,
                type int(10),
                PRIMARY KEY (endpointId, hostId, type)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
        $this->db->query($linkQuery);
    }

    private function activateChecksum()
    {
        $checksumQuery = "
            CREATE TABLE IF NOT EXISTS jtl_connector_checksum (
                endpointId int(10) unsigned NOT NULL,
                type tinyint unsigned NOT NULL,
                checksum varchar(255) NOT NULL,
                PRIMARY KEY (endpointId)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
        $this->db->query($checksumQuery);
    }

}
