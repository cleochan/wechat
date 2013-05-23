<?php

class Database_Table_SystemService extends Zend_Db_Table
{
	protected $_name = 'system_service';
	var $service_id;
	
	function GetServiceInfo()
	{
		if($this->service_id)
		{
			$row = $this->fetchRow($this->service_id);
			
			if(!empty($row))
			{
				$result = $row->toArray();
			}else{
				$result = NULL;
			}
		}else{
			$result = NULL;
		}
		
		return $result;
	}
	
	function MakeServiceObject()
	{
		if($this->service_id)
		{
			$get_service_info = $this->GetServiceInfo();
			
			if(!$get_service_info['wsdl_cache_enabled'])
			{
				ini_set("soap.wsdl_cache_enabled", "0");
			}
			
			$client = new SoapClient($get_service_info['service_wsdl_file_url']);
		}else{
			$client = NULL;
		}
		
		return $client;
	}
	
	function GetServiceList()
	{
		$services = $this->fetchAll("service_status=1");
		$services = $services->toArray();
		
		$result = array();
		
		if(!empty($services))
		{
			foreach($services as $s)
			{
				$result[$s['service_id']] = $s['service_name'];
			}
		}
		
		return $result;
	}
}