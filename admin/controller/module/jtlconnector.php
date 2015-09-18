<?php

class ControllerModuleJtlconnector extends Controller
{
    const CONNECTOR_VERSION = '1.0';
    const CONFIG_KEY = 'connector';
    const CONFIG_PASSWORD_KEY = 'connector_password';
    private $error = [];

    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->load->model('setting/setting');
    }

    public function index()
    {
        $this->load->language('module/jtlconnector');

        $this->document->setTitle($this->language->get('heading_title'));

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting(self::CONFIG_KEY, $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'],
                'SSL'));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_info'] = $this->language->get('text_info');
        $data['text_url'] = $this->language->get('text_url');
        $data['text_password'] = $this->language->get('text_password');
        $data['text_version'] = $this->language->get('text_version');

        $data['url'] = $this->config->get('config_url') . 'jtlconnector/';
        $data['version'] = self::CONNECTOR_VERSION;

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('module/jtlconnector', 'token=' . $this->session->data['token'], 'SSL')
        );

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

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'module/jtlconnector')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function install()
    {
        $linkQuery = "
            CREATE TABLE IF NOT EXISTS jtl_connector_link (
                endpointId char(64) NOT NULL,
                hostId int(10) NOT NULL,
                type int(10),
                PRIMARY KEY (endpointId, hostId, type)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
        $this->db->query($linkQuery);

        // TODO: product checksum, unit, delivery note, payment

        $this->model_setting_setting->editSetting(self::CONFIG_KEY, [self::CONFIG_PASSWORD_KEY => '']);
    }

    public function uninstall()
    {
        $this->db->query('DROP TABLE jtl_connector_link');
        $this->model_setting_setting->deleteSetting(self::CONFIG_KEY);
    }

}
