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
}