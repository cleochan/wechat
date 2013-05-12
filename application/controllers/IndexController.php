<?php

class IndexController extends Zend_Controller_Action
{
  
	
    function init()
    {
        $this->db = Zend_Registry::get("db");
    }
	
    function preDispatch()
    {  
		$this->wechat_model = new Core_Wechat();
		$this->data = $this->wechat_model->ReceiveMsgObject($this->wechat_model->ReceiveMsgXml());
		$data_json = Zend_Json::encode($this->data);
		$this->system_log_transaction_model = new Database_Table_SystemLogTransaction();
		$this->system_log_transaction_model->InsertLog($data_json);
    }
	
    function indexAction()
    {
        
    	
        die;
    }
}

