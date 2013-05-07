<?php

class Database_Table_LogsTransaction extends Zend_Db_Table
{
	function ReceiveMsg()
	{
		$data = $GLOBALS["HTTP_RAW_POST_DATA"];
		return simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
	}
	
	function InsertLog($log_contents)
	{
		$row = $this->createRow();
		$row->log_time = date("Y-m-d H:i:s");
		$row->log_contents = $log_contents;
		$row->save();
	}
}