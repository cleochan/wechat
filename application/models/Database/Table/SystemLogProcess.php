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
			
			$system_log_process_id = $row->save();
			
		}else{
			$error = "Key parameter missing.";
		}
	}
}