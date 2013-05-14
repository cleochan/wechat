<?php

class IndexController extends Zend_Controller_Action
{
  
	
    function init()
    {
        $this->db = Zend_Registry::get("db");
    }
	
    function preDispatch()
    {  
		//save basic transaction log
    	$this->wechat_model = new Core_Wechat();
		$this->data = $this->wechat_model->ReceiveMsgObject($this->wechat_model->ReceiveMsgXml());
		$data_json = Zend_Json::encode($this->data);
		$this->system_log_transaction_model = new Database_Table_SystemLogTransaction();
		$this->system_log_transaction_model->InsertLog($data_json);
    }
	
    function indexAction()
    {
        //get last log
    	$system_log_process_model = new Database_Table_SystemLogProcess();
    	$get_last_log = $system_log_process_model->GetLastLog($this->data->FromUserName);
        
        //save process log
    	$system_log_process_model = new Database_Table_SystemLogProcess();
        $system_log_process_model->wechat_ref = $this->data->FromUserName;
        $system_log_process_model->issue_key = "E1"; //text
        $system_log_process_model->issue_value = $this->data->Content;
        $system_log_process_model->InsertLog();
        
        //find service
        $service_info = $system_log_process_model->ServicePoint($this->data, $get_last_log);
        
        if($service_info[0])
        {
        	$class_name = "Service_".$service_info[1];
        	$target_module = new $class_name();
        }else{ //no service found
        	$target_module = $this->wechat_model;
        }
        
        //proeed request
        $target_module->post_object = $this->data;
        $target_module->user_id = $service_info[4];
        $target_module->service_id = $service_info[0];
        $target_module->service_user_id = $service_info[5];
        $target_module->issue_key = $service_info[2];
        $target_module->issue_value = $service_info[3];
        $proceed_result = $target_module -> Proceed();
        
        /**
         * $proceed_result[0] = array( //FOR PROCESS LOG
         * 								"user_id" =>
         *								"service_id" => INITIAL VALUE
         *								"service_user_id" =>
         *								"wechat_ref" => INITIAL VALUE
         *								"issue_key" =>
         *								"issue_value" =>
         * 							)
         * $proceed_result[1] = array( //FOR RESPONSE MESSAGE
         * 								"response_contents" =>
         * 							)
         */
        
        /********************* RESPONSE REQUEST ********************/
        
        //save process log
        $system_log_process_model_for_reply = new Database_Table_SystemLogProcess();
        $system_log_process_model_for_reply->user_id = $proceed_result[0]['user_id'];
        $system_log_process_model_for_reply->service_id = $service_info[0];
        $system_log_process_model_for_reply->service_user_id = $proceed_result[0]['service_user_id'];
        $system_log_process_model_for_reply->wechat_ref = $this->data->FromUserName;
        $system_log_process_model_for_reply->issue_key = $proceed_result[0]['issue_key'];
        $system_log_process_model_for_reply->issue_value = $proceed_result[0]['issue_value'];
        $system_log_process_model_for_reply->InsertLog();
        
        //response message to user interface
        $this->wechat_model->ResponseMsg($this->data, $proceed_result[1]["response_contents"]);
    	
        die;
    }
}

