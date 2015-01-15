<?php
class UserController extends Zend_Controller_Action
{

	function loginAction() {
		$this->_helper->layout->setLayout('login');
		$session = new Zend_Session_Namespace('lastRequest');
		if ($session->lastRequestUri) {
			$this->view->url = $session->lastRequestUri;
		} else {
			$this->view->url = '/';
		}
		if ($this->_request->isPost()) {
			$username = $this->_request->getPost('username');
			$password = $this->_request->getPost('password');
			if (empty($username)) {
				$this->view->message = 'Please provide a username.';
				return;
			}

			if (empty($password)) {
				$this->view->message = 'Please provide a password.';
				return;
			}

			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
			$authAdapter->setTableName('users');
			$authAdapter->setIdentityColumn('username');
			$authAdapter->setCredentialColumn('password');
			$authAdapter->setIdentity($username);
			$authAdapter->setCredential(md5($password));

			$auth = Zend_Auth::getInstance();
			$result = $auth->authenticate($authAdapter);

			if ($result->isValid()) {
				$auth = Zend_Auth::getInstance();
				$storage = $auth->getStorage();
				$storage->write($authAdapter->getResultRowObject(
					array('id', 'username', 'first_name', 'last_name', 'role')));
				$this->_redirect($this->view->url);
			} else {
				$this->view->message = 'Invalid username/password combination.  Try again.';
			}
		}
	}

	public function logoutAction()
	{
		$authAdapter = Zend_Auth::getInstance();
		$authAdapter->clearIdentity();
		$this->view->message = 'You have been logged out.';
		$this->_redirect('/user/login');
	}
}
