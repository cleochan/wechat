<?php
class Core_Helper
{
	function ReceiveKeywordFilter($contents)
	{
		return strtolower(trim($contents));
	}
	
	function OptionsDecolation($options_array, $with_key=NULL)
	{
		$result = "";
		$n = 1;
		
		if(count($options_array))
		{
			$result .= "\n";
			
			foreach($options_array as $option_key => $option_value)
			{
				if($with_key)
				{
					$result .= $option_key." - ".$option_value."\n";
				}else{
					$result .= $n." - ".$option_value."\n";
					$n += 1;
				}
			}

			$result .= "\n";
		}
		
		return $result;
	}
	
	function NoticesDecolation($options_array)
	{
		$result_string = "";
		
		if(count($options_array))
		{
			$result .= "\n";
			
			foreach($options_array as $option_value)
			{
				$result .= "* - ".$option_value."\n";
			}

			$result .= "\n";
		}
		
		return $result_string;
	}
	
	function HeaderDecolation($string)
	{
		return $string."\n";
	}
	
	function FooterDecolation($string)
	{
		return $string;
	}
}