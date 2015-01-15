<?php

class IndexController extends Zend_Controller_Action
{
	function preDispatch()
	{
		$auth = Zend_Auth::getInstance();
		if (!$auth->hasIdentity()) {
		        $this->_redirect('/user/login');
		} else {
			$this->view->identity = $auth->getIdentity();
		}
	}

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }


}

