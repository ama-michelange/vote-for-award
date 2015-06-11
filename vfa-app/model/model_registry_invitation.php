<?php

/**
 * Class model_registry_invitation
 */
class model_registry_invitation extends abstract_model
{

	protected $sClassRow = 'row_registry_invitation';

	protected $sTable = null;

	protected $sConfig = null;
}

class row_registry_invitation extends abstract_row
{

	protected $sClassModel = 'model_registry_invitation';

	protected $tMessages = null;

	private function getCheck()
	{
		$oPluginValid = new plugin_valid($this->getTab());
		$oPluginValid->isNotEmpty('email');
		if (null != $this->__get('email')) {
			$oPluginValid->isEmailValid('email');
		}
		$oPluginValid->isNotEmptyOr('award_id', 'awards_ids');
		$oPluginValid->isNotEmpty('group_id');
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
