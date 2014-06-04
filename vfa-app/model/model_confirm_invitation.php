<?php

class model_confirm_invitation extends abstract_model
{

	protected $sClassRow = 'row_confirm_invitation';

	protected $sTable = null;

	protected $sConfig = null;
}

class row_confirm_invitation extends abstract_row
{

	protected $sClassModel = 'model_confirm_invitation';

	protected $tMessages = null;

	private function getCheck()
	{
		$oPluginValid = new plugin_valid($this->getTab());
		switch ($this->__get('action')) {
			case 'toIdentify':
				$oPluginValid->isNotEmpty('cf_login');
				$oPluginValid->isNotEmpty('cf_password');
				break;
			case 'toRegistry':
				$oPluginValid->isNotEmpty('login');
				$oPluginValid->isNotEmpty('email');
				$bEmailValid = false;
				if (null != $this->__get('email')) {
					$bEmailValid = $oPluginValid->isEmailValid('email');
				}
//				$oPluginValid->isNotEmpty('email_bis');
//				$bEmailBisValid = false;
//				if (null != $this->__get('email_bis')) {
//					$bEmailBisValid = $oPluginValid->isEmailValid('email_bis');
//				}
//				if ($bEmailValid && $bEmailBisValid) {
//					$oPluginValid->isEqual('email', 'email_bis');
//					$oPluginValid->isEqual('email_bis', 'email');
//				}
				$oPluginValid->isNotEmpty('password');
				$oPluginValid->isNotEmpty('password_bis');
				if ((null != $this->__get('password')) && (null != $this->__get('password_bis'))) {
					$oPluginValid->isEqual('password', 'password_bis');
					$oPluginValid->isEqual('password_bis', 'password');
				}
				break;
			default:
				// Valeur non vérifiable de manière à ne jamais être valide !
				$oPluginValid->isNotEmpty('action');
				$oPluginValid->isEqual('action', '__action__');
				break;
		}
		return $oPluginValid;
	}

	public function isValid()
	{
		return $this->getCheck()->isValid();
	}

	public function getListError()
	{
		return $this->getCheck()->getListError();
	}

	public function setMessages($ptMessages)
	{
		$this->tMessages = $ptMessages;
	}

	public function getMessages()
	{
		if (null != $this->tMessages) {
			return $this->tMessages;
		}
		return $this->getListError();
	}
}