<?php 
class Service_Ticket
{
	var $service_id;
	var $post_object;
	var $issue_key;
	var $issue_value;
	
	//consts for issue key
	const ERROR_KEY_PARAMETER_MISSING = 0;
	
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
    		$result_for_process_log = array(
    				"user_id" => NULL,
    				"service_user_id" => NULL,
    				"issue_key" => 2,
    				"issue_value" => "success"
    		);
    		
    		$result_for_response_message = array(
    				"response_contents" => "ticket success"
    		);
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
}