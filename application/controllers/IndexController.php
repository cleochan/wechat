<?php

class IndexController extends Zend_Controller_Action
{
  
	
    function init()
    {
        $this->db = Zend_Registry::get("db");
    }
	
    function preDispatch()
    {  
		$logs_transaction_model = new Database_Table_LogsTransaction();
		$data = $logs_transaction_model->ReceiveMsg();
		$data_json = Zend_Json::encode($data);
		$logs_transaction_model->InsertLog($data_json);
		echo "here";
		die;
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

