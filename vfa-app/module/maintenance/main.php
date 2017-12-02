<?php

class module_maintenance extends abstract_module
{
    public function before()
    {
        plugin_vfa::loadI18n();
        $this->oLayout = new _layout('tpl_bs_bar');
    }

    public function _index()
    {
        $oView = new _view('maintenance::index');
        $this->oLayout->add('work', $oView);
    }
    public function after()
    {
        $this->oLayout->show();
    }
}
