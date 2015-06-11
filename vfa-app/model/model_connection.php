<?php

/**
 * Class model_connection
 */
class model_connection extends abstract_model
{

	protected $sClassRow = 'row_connection';

	protected $sTable = null;

	protected $sConfig = null;
}

class row_connection extends abstract_row
{

	protected $sClassModel = 'model_connection';

	protected $tMessages = null;

	private function getCheck()
	{
		$oPluginValid = new plugin_valid($this->getTab());

		switch ($this->__get('action')) {
			case 'submitForgottenPassword':
				$oPluginValid->isNotEmpty('myEmail');
				if (null != $this->__get('myEmail')) {
					$oPluginValid->isEmailValid('myEmail');
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
