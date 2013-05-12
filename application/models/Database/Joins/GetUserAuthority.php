<?php
class Database_GetUserAuthority
{
	function __construct(){
		$this->db = Zend_Registry::get("db");
	}
	
	function GetUserServiceArray($search_keyword)
	{
		$rows = $this->db->select();
		$rows->from("system_user as u", array("service_id as sid"));
		$rows->joinLeft("system_service as s", "s.service_id=u.service_id", array("service_name as sname"));
		$rows->where("s.status = ?", 1); //Activated
		$rows->where("u.status = ?", 1); //Activated
		$rows->where("u.wechat_ref = ?", $search_keyword);
		
		$data = $this->db->fetchAll($rows);
		
		$result = array();
		
		if(!empty($data))
		{
			foreach($data as $d)
			{
				$result[$d['sid']] = $d['sname'];
			}
		}
		
		return $result;
	}
}