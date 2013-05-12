<?php

class Core_Wechat
{   
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
    	
    	$service_element_array = array();
    	
    	if(!empty($service_array))
    	{
    		foreach($service_array as $s_key => $s_val)
    		{
    			$service_element_array[] = $s_key." - ".$s_val;
    		}
    		
    		$service_msg = implode("\n", $service_element_array);
    	}
    	
    	$msg = "Welcome to Mark API, please choose your service:\n\n";
    	$msg .= $service_msg;
    	$msg .= "\n\nPlease note: Anytime you would like to be back to this screen, send message as 'exit' or 'quit', enjoy!";
    	
    	return $msg;
    }
}



