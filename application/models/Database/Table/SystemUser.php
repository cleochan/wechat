<?php

class Database_Table_SystemUser extends Zend_Db_Table
{
	protected $_name = 'system_user';
	var $service_id;
	var $service_user_id;
	var $wechat_ref;
	var $status = 1; //Activated
	
	function UserRegistration()
	{
		$error = NULL;
		
		if($this->service_id && $this->service_user_id && $this->wechat_ref)
		{
			$row = $this->createRow();
			$row->service_id = $this->service_id;
			$row->service_user_id = $this->service_user_id;
			$row->wechat_ref = $this->wechat_ref;
			$row->status = $this->status;
			$row->join_date = date("Y-m-d H:i:s");
			$user_id = $row->save();
		}else{
			$error = "Key parameters missing.";
		}
		
		$result = array();
		
		if($user_id)
		{
			$result[0] = "Y";
			$result[1] = $user_id;
		}else{
			$result[0] = "N";
			$result[1] = $error;
		}
		
		return $result;
	}
	
	function UserExisted() //RETURN BOOLEAN
	{
		$result = FALSE;
		
		if($this->service_id && $this->wechat_ref)
		{
			$row = $this->fetchRow("service_id='".$this->service_id."' and wechat_ref='".$this->wechat_ref."'");
			
			if($row->user_id)
			{
				$result = TRUE;
			}
		}
		
		return $result;
	}
}