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
    					"username" => "aa",
    					"password" => md5("bb")
    			)
    	);
    	
    	$result = $wsdl->WsdlClient(1, $post_array);
    	
    	echo ">>".$result."<<";
    	die;
    }
    
    function dumpObjAction()
    {
    	$service_model = new Database_Table_SystemService();
    	$service_model->service_id = 1;
    	$client = $service_model->MakeServiceObject();
    	echo "<pre>";
    	print_r($client);
    	echo "<pre>";
    	die;
    }
}

