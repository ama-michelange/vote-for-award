<?php

class model_registry extends abstract_model
{

	protected $sClassRow = 'row_registry';

	protected $sTable = null;

	protected $sConfig = null;
}

class row_registry extends abstract_row
{

	protected $sClassModel = 'model_registry';

	protected $tMessages = null;

	private function getCheck()
	{
		$oPluginValid = new plugin_valid($this->getTab());

		switch ($this->__get('action')) {
			case 'toGetCode':
				$oPluginValid->isNotEmpty('code');
				break;
			case 'toAccount':
				$oPluginValid->isNotEmpty('login');
				$oPluginValid->isNotEmpty('email');
				if (null != $this->__get('email')) {
					$oPluginValid->isEmailValid('email');
				}
				$oPluginValid->isNotEmpty('confirmEmail');
				if (null != $this->__get('confirmEmail')) {
					$oPluginValid->isEmailValid('confirmEmail');
				}
				if ((null != $this->__get('email')) && (null != $this->__get('confirmEmail'))) {
					$oPluginValid->isEqual('email', 'confirmEmail');
					$oPluginValid->isEqual('confirmEmail', 'email');
				}
				$oPluginValid->isNotEmpty('newPassword');
				$oPluginValid->isNotEmpty('confirmPassword');

				if (null != $this->__get('newPassword')) {
					$oPluginValid->isLengthBetween('newPassword', 7, 50);
					if ((strlen($this->__get('newPassword')) >= 7) && (strlen($this->__get('newPassword')) <= 50)) {
						if (null != $this->__get('confirmPassword')) {
							$oPluginValid->isEqual('newPassword', 'confirmPassword');
							$oPluginValid->isEqual('confirmPassword', 'newPassword');
						}
					}
				}
				$oPluginValid->isNotEmpty('last_name');
				$oPluginValid->isNotEmpty('first_name');
				break;
			case 'toIdentify':
				$oPluginValid->isNotEmpty('cf_login');
				$oPluginValid->isNotEmpty('cf_password');
				break;
			case 'toNewPassword':
				$oPluginValid->isNotEmpty('newPassword');
				$oPluginValid->isNotEmpty('confirmPassword');
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

	public function toStringType()
	{
		return plugin_i18n::get('role.' . $this->type);
	}
}