<?php

class Database_Table_SystemLogProcess extends Zend_Db_Table
{
	protected $_name = 'system_log_process';
	var $user_id;
	var $service_id;
	var $service_user_id;
	var $wechat_ref;
	var $issue_key;
	var $issue_value;
	
	function InsertLog($postObj)
	{
		$this->wechat_ref = $postObj->FromUserName;
		$this->issue_key = "E1"; //text from sender
		$this->issue_value = $postObj->Content;
		
		$error = NULL;
		
		if($this->wechat_ref) // wechat_ref is a required param
		{
			$row = $this->createRow();
			
			$row->wechat_ref = $this->wechat_ref;
			
			if($this->user_id)
			{
				$row->user_id = $this->user_id;
			}
			if($this->service_id)
			{
				$row->service_id = $this->service_id;
			}
			if($this->service_user_id)
			{
				$row->service_user_id = $this->service_user_id;
			}
			if($this->issue_key)
			{
				$row->issue_key = $this->issue_key;
			}
			if($this->issue_value)
			{
				$row->issue_value = $this->issue_value;
			}
			
			$row->issue_time = date("Y-m-d H:i:s");
			
			$system_log_process_id = $row->save();
			
		}else{
			$error = "Key parameter missing.";
		}
	}
	
	function GetLastLog($wechat_ref)
	{
		$row = $this->fetchRow("wechat_ref='".$wechat_ref."'", "system_log_process_id DESC");
		
		return $row->toArray();
	}
	
	function ServicePoint($postObj)
	{
		$last_log = $this->GetLastLog($postObj->FromUserName);
		
		if(!empty($last_log))
		{
			$service = $last_log['service_id'];
		}else{
			$service = 0;
		}
		
		if($service)
		{
			$system_service_model = new Database_Table_SystemService();
			$system_service_model->service_id = $service;
			$service_info = $system_service_model->GetServiceInfo();
			$service_name = ucwords($service_info['service_code']);
		}else{
			$service_name = NULL;
		}
		
		return $service_name;
	}
	
	
	
}