<?php

class Core_Wechat
{   
	var $post_object;
	var $user_id;
	var $service_id;
	var $service_user_id;
	var $issue_key;
	var $issue_value;
    
    //consts for issue key
    const ERROR_KEY_PARAMETER_MISSING = 0;
    const ANONYMOUS_DEFAULT_MSG_SENT = 1;
	
	function ReceiveMsgXml()
    {
        return $GLOBALS["HTTP_RAW_POST_DATA"];
    }
    
    function ReceiveMsgObject($receive_msg_xml)
    {
    	return simplexml_load_string($receive_msg_xml, 'SimpleXMLElement', LIBXML_NOCDATA);
    }
    
    function ResponseMsg($postObj, $response_contents)
    {
		$fromUsername = $postObj->FromUserName;
		$toUsername = $postObj->ToUserName;
		$response_contents = trim ( $response_contents );
		$time = time ();
		
		$textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                        </xml>";
		if (! empty ( $response_contents )) {
			$msgType = "text";
			$contentStr = $response_contents;
			$resultStr = sprintf ( $textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr );
			echo $resultStr;
		} else {
			echo "Input something...";
		}
    }
    
    function AnonymousDefaultMsg()
    {
    	$system_service = new Database_Table_SystemService();
    	$service_array = $system_service->GetServiceList();
    	$helper = new Core_Helper();
    	
    	$msg = $helper->HeaderDecolation("Welcome to Mark API, please choose your service:");
    	$msg .= $helper->OptionsDecolation($service_array, TRUE);
    	$msg .= $helper->FooterDecolation("Please note: Anytime you would like to be back to this screen, send message as 'exit' or 'quit', enjoy!");
    	
    	return $msg;
    }
    
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
    function Proceed() //for requests without service
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
    	
    	if(!$this->service_id && $this->post_object) //proceed
    	{
    		$result_for_process_log = array(
    				"user_id" => NULL,
    				"service_user_id" => NULL,
    				"issue_key" => self::ANONYMOUS_DEFAULT_MSG_SENT,
    				"issue_value" => NULL
    		);
    		
    		$result_for_response_message = array(
    				"response_contents" => $this->AnonymousDefaultMsg()
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
    
    function QuitCommands($command=NULL)
    {
    	$quit_commands = array("quit", "exit");
    	
    	if($command)
    	{
    		$helper = new Core_Helper();
    		if(in_array($helper->ReceiveKeywordFilter($command), $quit_commands))
    		{
    			$result = TRUE;
    		}else{
    			$result = FALSE;
    		}
    	}else{
    		$result = $quit_commands;
    	}
    	
    	return $result;
    }
}



