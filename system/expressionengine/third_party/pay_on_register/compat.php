<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//--------------------------------------------
//	Alias to get_instance() < EE 2.6.0 backward compat
//--------------------------------------------
if ( ! function_exists('ee'))
{
	function ee()
	{
		static $EE;
		if ( ! $EE) $EE = get_instance();
		return $EE;
	}
}

//--------------------------------------------
//	short dump version
//--------------------------------------------
if ( ! function_exists('dumper'))
{
	function dumper($data = '', $stop = false)
	{
		echo '<pre>';
		print_r($data);
		echo '</pre>';

		if($stop)
		{
			exit;
		}
	}
}