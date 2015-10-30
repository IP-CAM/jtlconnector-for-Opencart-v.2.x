<?php

class ControllerModuleJtlconnector extends Controller
{
    const CONNECTOR_VERSION = '0.6.2';
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

    //// <editor-fold defaultstate="collapsed" desc="Edit Action">
    public function index()
    {
        if (version_compare(VERSION, '2.0.3.1', '>')) {
            $this->language->load('module/jtlconnector');
        } else {
            $this->load->language('module/jtlconnector');
        }

        $this->document->setTitle($this->language->get('heading_title'));

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->handleCustomFields();

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
        $data['text_sqlite'] = $this->language->get('text_sqlite');
        $data['text_zipping'] = $this->language->get('text_zipping');
        $data['text_free_fields'] = $this->language->get('text_free_fields');
        $data['text_free_field_salutation'] = $this->language->get('text_free_field_salutation');
        $data['text_free_field_title'] = $this->language->get('text_free_field_title');
        $data['text_free_field_vat_number'] = $this->language->get('text_free_field_vat_number');

        $data['url'] = HTTP_CATALOG . 'jtlconnector/';
        $data['version'] = self::CONNECTOR_VERSION;
        $data['php_version'] = version_compare(PHP_VERSION, '5.4', '>=');
        $data['sqlite'] = extension_loaded('sqlite3') && class_exists('Sqlite3');
        $data['zipping'] = class_exists('ZipArchive');
        $data['write_access'] = $this->writeAccess();
        $data['salutation_activated'] = $this->salutationActivated();
        $data['title_activated'] = $this->titleActivated();
        $data['vat_activated'] = $this->vatActivated();
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
        $logsPath = DIR_LOGS . 'jtlconnector/';
        $imagePath = DIR_IMAGE . 'data/';
        return [
            $configPath => is_file($configPath) && is_writable($configPath),
            $logsPath => is_dir($logsPath) && is_writable($logsPath),
            $imagePath => is_dir($imagePath) && is_writable($imagePath)
        ];
    }

    private function salutationActivated()
    {
        $result = $this->db->query('
            SELECT *
            FROM ' . DB_PREFIX . 'custom_field_description
            WHERE name IN ("Anrede", "Salutation")');
        return $result->num_rows > 0;
    }

    private function titleActivated()
    {
        $result = $this->db->query('
            SELECT *
            FROM ' . DB_PREFIX . 'custom_field_description
            WHERE name IN ("Titel", "Title")');
        return $result->num_rows > 0;
    }

    private function vatActivated()
    {
        $result = $this->db->query('
            SELECT *
            FROM ' . DB_PREFIX . 'custom_field_description
            WHERE name IN ("USt-IdNr.", "VAT number")');
        return $result->num_rows > 0;
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'module/jtlconnector')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
    //// </editor-fold>

    //// <editor-fold defaultstate="collapsed" desc="Install Action">
    public function install()
    {
        $this->activateLinking();
        $this->activateChecksum();
        $this->activateFilter();

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
        $categoryLayoutId = 3;
        $filterInstalled = 'SELECT * FROM ' . DB_PREFIX . 'extension WHERE type = "module" AND CODE = "filter"';
        if (empty($this->db->query($filterInstalled)->rows)) {
            $this->db->query('INSERT INTO ' . DB_PREFIX . 'extension (type, CODE) VALUES ("module", "filter")');
        }
        $filterSettings = $this->model_setting_setting->getSetting('filter');
        if (isset($filterSettings['filter_status'])) {
            if ($filterSettings['filter_status'] != 1) {
                $this->model_setting_setting->editSettingValue('filter', 'filter_status', '1');
            }
        } else {
            $filterSettings['filter_status'] = 1;
            $this->model_setting_setting->editSetting('filter', $filterSettings);
        }
        $filterInLayout = sprintf('
                SELECT *
                FROM ' . DB_PREFIX . 'layout_module
                WHERE layout_id = % AND CODE = "filter"',
            $categoryLayoutId
        );
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
    //// </editor-fold>

    //// <editor-fold defaultstate="collapsed" desc="Custom Fields">
    private function handleCustomFields()
    {
        $post = $this->request->post;
        if (version_compare(VERSION, '2.0.3.1', '>')) {
            $this->load->model('customer/custom_field');
        } else {
            $this->load->model('sale/custom_field');
        }
        $german = $this->db->query('SELECT language_id FROM ' . DB_PREFIX . 'language WHERE code = "de"');
        $otherLanguages = $this->db->query('SELECT language_id FROM ' . DB_PREFIX . 'language WHERE code != "de"');
        if (isset($post['free_field_salutation']) && $post['free_field_salutation'] === 'on') {
            $this->handleCustomFieldSalutation($german, $otherLanguages);
        } else {
            $result = $this->db->query('
                    SELECT DISTINCT(custom_field_id)
                    FROM ' . DB_PREFIX . 'custom_field_description
                    WHERE name IN ("Anrede", "Salutation")');
            if ($result->num_rows > 0) {
                $this->setCustomFieldStatus($result->row['custom_field_id'], 0);
            }
        }
        if (isset($post['free_field_title']) && $post['free_field_title'] === 'on') {
            $this->handleCustomFieldTitle($german, $otherLanguages);
        } else {
            $result = $this->db->query('
                    SELECT DISTINCT(custom_field_id)
                    FROM ' . DB_PREFIX . 'custom_field_description
                    WHERE name IN ("Titel", "Title")');
            if ($result->num_rows > 0) {
                $this->setCustomFieldStatus($result->row['custom_field_id'], 0);
            }
        }
        if (isset($post['free_field_vat_number']) && $post['free_field_vat_number'] === 'on') {
            $this->handleCustomFieldVatNumber($german, $otherLanguages);
        } else {
            $result = $this->db->query('
                    SELECT DISTINCT(custom_field_id)
                    FROM ' . DB_PREFIX . 'custom_field_description
                    WHERE name IN ("USt-IdNr.", "VAT number")');
            if ($result->num_rows > 0) {
                $this->setCustomFieldStatus($result->row['custom_field_id'], 0);
            }
        }
    }

    private function handleCustomFieldSalutation($german, $otherLanguages)
    {
        $result = $this->db->query('
            SELECT DISTINCT(custom_field_id)
            FROM ' . DB_PREFIX . 'custom_field_description
            WHERE name IN ("Anrede", "Salutation")'
        );
        if ($result->num_rows === 0) {
            $data = [
                'type' => 'select',
                'value' => '',
                'location' => 'account',
                'status' => 1,
                'sort_order' => 1
            ];
            $customFieldDescription = [];
            $sirDescription = ['sort_order' => 1];
            $madamDescription = ['sort_order' => 2];
            $companyDescription = ['sort_order' => 3];
            if ($german->num_rows !== 0) {
                $customFieldDescription[$german->row['language_id']] = [
                    'name' => 'Anrede'
                ];
                $sirDescription['custom_field_value_description'][$german->row['language_id']] = [
                    'name' => 'Herr'
                ];
                $madamDescription['custom_field_value_description'][$german->row['language_id']] = [
                    'name' => 'Frau'
                ];
                $companyDescription['custom_field_value_description'][$german->row['language_id']] = [
                    'name' => 'Firma'
                ];
            }
            foreach ($otherLanguages->rows as $row) {
                $customFieldDescription[$row['language_id']] = [
                    'name' => 'Salutation'
                ];
                $sirDescription['custom_field_value_description'][$row['language_id']] = [
                    'name' => 'Sir'
                ];
                $madamDescription['custom_field_value_description'][$row['language_id']] = [
                    'name' => 'Madam'
                ];
                $companyDescription['custom_field_value_description'][$row['language_id']] = [
                    'name' => 'Company'
                ];
            }
            $data['custom_field_description'] = $customFieldDescription;
            $data['custom_field_value'] = [$sirDescription, $madamDescription, $companyDescription];
            $this->addCustomerGroup($data);
            if (version_compare(VERSION, '2.0.3.1', '>')) {
                $this->model_customer_custom_field->addCustomField($data);
            } else {
                $this->model_sale_custom_field->addCustomField($data);
            }
        } elseif ($result->num_rows === 1) {
            $this->setCustomFieldStatus($result->row['custom_field_id'], 1);
        }
    }

    private function addCustomerGroup(&$data)
    {
        $result = $this->db->query('SELECT customer_group_id FROM ' . DB_PREFIX . 'customer_group');
        foreach ($result->rows as $row) {
            $data['custom_field_customer_group'][] = [
                'customer_group_id' => $row['customer_group_id']
            ];
        }
    }

    private function handleCustomFieldTitle($german, $otherLanguages)
    {
        $result = $this->db->query('
            SELECT DISTINCT(custom_field_id)
            FROM ' . DB_PREFIX . 'custom_field_description
            WHERE name IN ("Titel", "Title")'
        );
        if ($result->num_rows === 0) {
            $data = [
                'type' => 'select',
                'value' => '',
                'location' => 'account',
                'status' => 1,
                'sort_order' => 2
            ];
            $customFieldDescription = [];
            $drDescription = ['sort_order' => 1];
            $profDescription = ['sort_order' => 2];
            if ($german->num_rows !== 0) {
                $customFieldDescription[$german->row['language_id']] = [
                    'name' => 'Titel'
                ];
                $drDescription['custom_field_value_description'][$german->row['language_id']] = [
                    'name' => 'Dr.'
                ];
                $profDescription['custom_field_value_description'][$german->row['language_id']] = [
                    'name' => 'Prof.'
                ];
            }
            foreach ($otherLanguages->rows as $row) {
                $customFieldDescription[$row['language_id']] = [
                    'name' => 'Title'
                ];
                $drDescription['custom_field_value_description'][$row['language_id']] = [
                    'name' => 'Dr.'
                ];
                $profDescription['custom_field_value_description'][$row['language_id']] = [
                    'name' => 'Prof.'
                ];
            }
            $data['custom_field_description'] = $customFieldDescription;
            $data['custom_field_value'] = [$drDescription, $profDescription];
            $this->addCustomerGroup($data);
            if (version_compare(VERSION, '2.0.3.1', '>')) {
                $this->model_customer_custom_field->addCustomField($data);
            } else {
                $this->model_sale_custom_field->addCustomField($data);
            }
        } elseif ($result->num_rows === 1) {
            $this->setCustomFieldStatus($result->row['custom_field_id'], 1);
        }
    }

    private function handleCustomFieldVatNumber($german, $otherLanguages)
    {
        $result = $this->db->query('
            SELECT DISTINCT(custom_field_id)
            FROM ' . DB_PREFIX . 'custom_field_description
            WHERE name IN ("USt-IdNr.", "VAT number")'
        );
        if ($result->num_rows === 0) {
            $data = [
                'type' => 'text',
                'value' => '',
                'location' => 'address',
                'status' => 1,
                'sort_order' => 1
            ];
            $customFieldDescription = [];
            if ($german->num_rows !== 0) {
                $customFieldDescription[$german->row['language_id']] = [
                    'name' => 'USt-IdNr.'
                ];
            }
            foreach ($otherLanguages->rows as $row) {
                $customFieldDescription[$row['language_id']] = [
                    'name' => 'VAT number'
                ];
            }
            $data['custom_field_description'] = $customFieldDescription;
            $this->addCustomerGroup($data);
            if (version_compare(VERSION, '2.0.3.1', '>')) {
                $this->model_customer_custom_field->addCustomField($data);
            } else {
                $this->model_sale_custom_field->addCustomField($data);
            }
        } elseif ($result->num_rows === 1) {
            $this->setCustomFieldStatus($result->row['custom_field_id'], 1);
        }
    }

    private function setCustomFieldStatus($id, $status)
    {
        $this->db->query(sprintf('
            UPDATE ' . DB_PREFIX . 'custom_field SET status = %d
            WHERE custom_field_id = %d', $status, $id
        ));
    }

    //// </editor-fold>

    public function uninstall()
    {
        $this->db->query('DROP TABLE IF EXISTS jtl_connector_link');
        $this->db->query('DROP TABLE IF EXISTS jtl_connector_checksum');
        $configs = $this->model_setting_setting->getSetting(self::CONFIG_KEY);
        $this->model_catalog_attribute_group->deleteAttributeGroup($configs[self::CONFIG_ATTRIBUTE_GROUP]);
        $this->model_setting_setting->deleteSetting(self::CONFIG_KEY);
    }
}