<?php
class Core_Wsdl
{
	/*
	 * $post_array(
	 * 				"request_type" => 
	 * 				"params" => array()
	 * 				)
	 */
	
	function WsdlClient($service_id, $post_array)
	{
		//get service info
		$service_model = new Database_Table_SystemService();
		$service_model->service_id = $service_id;
		$client = $service_model->MakeServiceObject();
		echo "[BEFORE]";
		$result = $client->S1($post_array); //post to server
		print_r($result);
		echo "[END]";
		die;
		return $result;
	}
}