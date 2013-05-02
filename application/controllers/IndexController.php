<?php

class IndexController extends Zend_Controller_Action
{
  
	
    function init()
    {
        $this->db = Zend_Registry::get("db");
    }
	
    function preDispatch()
    {  

    }
	
    function indexAction()
    {
        $wechat_class = new Core_Wechat();
        
        $receive_content = $wechat_class->ReceiveMsg();
        
        die;
    }
}

