<?php

class IndexController extends Zend_Controller_Action
{
  
	
    function init()
    {
        $this->db = Zend_Registry::get("db");
    }
	
    function preDispatch()
    {  
		$log_transaction_model = new Database_Table_LogTransaction();
		$this->data = $log_transaction_model->ReceiveMsg();
		$data_json = Zend_Json::encode($this->data);
		$log_transaction_model->InsertLog($data_json);
    }
	
    function indexAction()
    {
        $wechat_class = new Core_Wechat();
        
        $wechat_class->receive_content = $wechat_class->ReceiveMsg();
        
        $wechat_class->response_content = "OK1";
        
        $wechat_class->ResponseMsg();
        
        die;
    }
}

