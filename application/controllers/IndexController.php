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
		$data = $log_transaction_model->ReceiveMsg();
		$data_json = Zend_Json::encode($data);
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
    
    function t1Action()
    {
    	ini_set("soap.wsdl_cache_enabled", "0"); // disabling WSDL cache
    	 
    	$client = new SoapClient("http://t1.ciaomark.com/wsdl/ticket.wsdl");
    	
    	print_r($client->S1(2));
    	
    	die;
    }
}

