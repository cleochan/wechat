<?php
class Core_Helper
{
	function ReceiveKeywordFilter($contents)
	{
		return strtolower(trim($contents));
	}
}