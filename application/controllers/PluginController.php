<?php

class PluginController extends Zend_Controller_Action
{
  
	
    function init()
    {
        $this->db = Zend_Registry::get("db");
    }
	
    function indexAction()
    {
     	die;
    }
    
    function wsdlTestAction()
    {
    	$wsdl = new Core_Wsdl();
    	
    	$post_array = array(
    			"request_type" => "DetectIdentity",
    			"params" => array(
    					"username" => "AAA",
    					"password" => md5("BBB")
    			)
    	);
    	
    	$result = $wsdl->WsdlClient(1, $post_array);
    	
    	echo ">>".$result."<<";
    	die;
    }
    
    function testUserAction()
    {
    	$system_user_model = new Database_Table_SystemUser();
    	$system_user_model->service_id = 1;
    	$system_user_model->service_user_id = 22;
    	$system_user_model->wechat_ref = 33;
    	$system_user_model->UserRegistration();
    	echo "End.";
    	die;
    }
}

