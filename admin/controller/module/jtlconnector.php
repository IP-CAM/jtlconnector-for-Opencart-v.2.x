<?php
class ControllerModuleJtlconnector extends Controller 
{
	public function index() 
	{
		$this->load->language('module/banner');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module');
	}
	
	public function getProduct($id)
	{
		$this->load->model('catalog/product');
		return $this->model_catalog_product->getProduct($id);
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/jtlconnector')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if (!$this->request->post['width']) {
			$this->error['width'] = $this->language->get('error_width');
		}

		if (!$this->request->post['height']) {
			$this->error['height'] = $this->language->get('error_height');
		}

		return !$this->error;
	}

	public function install()
	{

	}
	
	public function uninstall()
	{
		
	}

}
