<?php

class Core_Wechat
{
    var $receive_content;
    var $response_content;
    
    function ReceiveMsg()
    {
        return $GLOBALS["HTTP_RAW_POST_DATA"];
    }
    
    function ResponseMsg()
    {
        if (!empty($this->receive_content)){

            $postObj = simplexml_load_string($this->receive_content, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
            $time = time();
            $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                        </xml>";             
            if(!empty( $keyword ))
            {
                $msgType = "text";
                $contentStr = $this->response_content;
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }else{
                echo "Input something...";
            }

        }else {
        	echo "";
        	exit;
        }
    }
}