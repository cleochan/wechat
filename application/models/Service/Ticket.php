<?php 
class Service_Ticket
{
	var $post_object;
	var $user_id;
	var $service_id;
	var $service_user_id;
	var $issue_key;
	var $issue_value;
	
	//consts for issue key
	const ERROR_KEY_PARAMETER_MISSING = 0;
	const REQUIRE_FOR_USERNAME = 'T1';
	const REQUIRE_FOR_PASSWORD = 'T2';
	const BK = 'T3';
	
    /**
     * $proceed_result[0] = array( //FOR PROCESS LOG
     * 								"user_id" =>
     *								"service_id" =>
     *								"service_user_id" =>
     *								"wechat_ref" =>
     *								"issue_key" =>
     *								"issue_value" =>
     * 							)
     * $proceed_result[1] = array( //FOR RESPONSE MESSAGE
     * 								"response_contents" =>
     * 							)
     */
    function Proceed()
    {
    	$helper = new Core_Helper();
    	$result = array();
    	
    	$result_for_process_log = array(
    			"user_id" => NULL,
    			"service_user_id" => NULL,
    			"issue_key" => NULL,
    			"issue_value" => NULL
    	);
    	
    	$result_for_response_message = array(
    			"response_contents" => NULL
    	);
    	
    	if($this->service_id && $this->post_object) //proceed
    	{
    		if(1 == $this->issue_key) //come to service dashboard
    		{
    			//detect identity
    			//$detect_result = $this->DetectIdentity($this->service_id, $this->post_object->FromUserName);
    			$system_user_model = new Database_Table_SystemUser();
    			$system_user_model->service_id = $this->service_id;
    			$system_user_model->wechat_ref = $this->post_object->FromUserName;
    			if($system_user_model->UserExisted()) //Existed User
    			{
    				
    			}else{ // New User, Login to ticket system
    				$options = array("Login", "Go Back");
    				
    				$msg = $helper->HeaderDecolation("You are in TICKET SYSTEM.");
    				$msg .= $helper->OptionsDecolation($options);
    				
    				$result_for_process_log = array(
    						"user_id" => NULL,
    						"service_user_id" => NULL,
    						"issue_key" => self::REQUIRE_FOR_USERNAME,
    						"issue_value" => NULL
    				);
    				
    				$result_for_response_message = array(
    						"response_contents" => $msg
    				);
    			}
    		}elseif("T1" == $this->issue_key) //require for username
    		{
    			$msg = $helper->HeaderDecolation("Please enter your username in ticket system:");
    			
    			$result_for_process_log = array(
    					"user_id" => NULL,
    					"service_user_id" => NULL,
    					"issue_key" => self::REQUIRE_FOR_PASSWORD,
    					"issue_value" => $this->post_object->content
    			);
    			
    			$result_for_response_message = array(
    					"response_contents" => $msg
    			);
    		}elseif("T2" == $this->issue_key) //require for password
    		{
    			$msg = $helper->HeaderDecolation("bk");
    			
    			$result_for_process_log = array(
    					"user_id" => NULL,
    					"service_user_id" => NULL,
    					"issue_key" => self::BK,
    					"issue_value" => NULL
    			);
    			
    			$result_for_response_message = array(
    					"response_contents" => $msg
    			);
    		}
    		

    	}else{ //incorrect response
    		$result_for_process_log = array(
    				"user_id" => NULL,
    				"service_user_id" => NULL,
    				"issue_key" => self::ERROR_KEY_PARAMETER_MISSING,
    				"issue_value" => "Error: Key Parameter Missing."
    		);
    		
    		$result_for_response_message = array(
    				"response_contents" => "Error: Key Parameter Missing."
    		);
    	}
    	
    	$result = array(
    			0 => $result_for_process_log,
    			1 => $result_for_response_message
    	);
    	
    	return $result;
    }
    
    function DetectIdentity($service_id, $wechat_ref)
    {
    	$post_array = array(
    			"request_type" => "DetectIdentity",
    			"params" => array(
    					"wechat_ref" => $wechat_ref
    				)
    			);
    	
    	$wsdl_model = new Core_Wsdl();
    	$result = $wsdl_model->WsdlClient($service_id, $post_array);
    	
    	return $result;
    }
}