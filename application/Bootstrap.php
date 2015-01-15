<?php

date_default_timezone_set('America/New_York');

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initDoctype()
	{
		$this->bootstrap('view');
		$view = $this->getResource('view');
		$view->doctype('HTML5');
	}

	protected function _initViewHelpers()
	{
		$front  = Zend_Controller_Front::getInstance();
		$router = $front->getRouter();
		$router->addRoute('viewInvoice',
			new Zend_Controller_Router_Route('invoice/view/:id', array(
				'controller' => 'invoice',
				'action'     => 'view'
			))
		);
	}
}

