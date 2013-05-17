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
}

