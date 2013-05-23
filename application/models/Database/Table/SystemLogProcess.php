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
	
	function InsertLog()
	{
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
		//just fetch the data in 6 hours
		$time_six_hour_ago = date("Y-m-d H:i:s", mktime(date("H")-6, date("i"), date("s"), date("m"), date("d"), date("Y")));
		
		$row = $this->fetchRow("wechat_ref='".$wechat_ref."' and issue_time >= '.$time_six_hour_ago.'", "system_log_process_id DESC");
		
		if(!empty($row))
		{
			$result = $row->toArray();
		}else{
			$result = array();
		}
		
		return $result;
	}
	
	function ServicePoint($postObj, $last_log)
	{
		$helper_model = new Core_Helper();
		
		$issue_key = NULL;
		$issue_value = NULL;
		$user_id = NULL;
		$service_user_id = NULL;
		
		if(!empty($last_log))
		{
			if($last_log['service_id'])
			{
				$service = $last_log['service_id'];
			}else{ //service id is null
				if(1 == $last_log['issue_key']) //anonymous msg sent
				{
					$service = $helper_model->ReceiveKeywordFilter($postObj->Content);
				}else{
					$service = 0;
				}
			}
			
			$issue_key = $last_log['issue_key'];
			$issue_value = $last_log['issue_value'];
			$user_id = $last_log['user_id'];
			$service_user_id = $last_log['service_user_id'];
			
		}else{
			$service = 0;
		}
		
		if($service)
		{
			$system_service_model = new Database_Table_SystemService();
			$system_service_model->service_id = $service;
			$service_info = $system_service_model->GetServiceInfo();
			if(!empty($service_info))
			{
				$service_name = ucwords($service_info['service_code']);
			}else{
				$service_name = NULL;
			}
		}else{
			$service_name = NULL;
		}
		
		if(!$service_name)
		{
			$service = 0; //service is not found in db
		}
		
		return array(
				0 => $service, 
				1 => $service_name,
				2 => $issue_key,
				3 => $issue_value,
				4 => $user_id,
				5 => $service_user_id
		);
	}
	
	
	
}