<?php

class Database_Table_SystemParameter extends Zend_Db_Table
{
	protected $_name = 'system_parameter';
	
	function GetVal($param_key)
	{
		$row = $this->fetchRow("param_key='".$param_key."'");
		if(!empty($row))
		{
			$result = $row['param_value'];
		}else{
			$result = NULL;
		}
		
		return $result;
	}
}