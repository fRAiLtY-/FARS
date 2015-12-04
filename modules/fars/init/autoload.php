<?php

class Fars {

	public static function show_error()
	{
		if (array_key_exists('system', Phpr::$session->flash->flash))
		{
			$system_message = Phpr::$session->flash['system'];
	
			if (strpos($system_message, 'flash_partial') !== false && !array_key_exists('error', Phpr::$session->flash->flash))
			{
				$partial_name = substr($system_message, 14);
				$success_message = array_key_exists('success', Phpr::$session->flash->flash) ? Phpr::$session->flash->flash['success'] : null;
				
				Cms_Controller::get_instance()->render_partial($partial_name, array('message'=>$success_message));
	
				Phpr::$session->flash->now();
				return;
			}
		}
	
		return static::flash();
	}
	
	
	private static function flash()
	{
		$result = null;

		foreach( Phpr::$session->flash as $type => $message )
		{
			if ($type == 'system')
				continue;
				
				
			switch ($type) {
				 case 'system':
				 	$type = 'info';
				 	break;
				 case 'error':
				 	$type = 'danger';
				 	break;
				 case 'success';
				 	$type = 'success';
				 	break;
				 default:
				 	$type = 'warning';
			}

			$result .= '<div class="alert alert-'.$type.'"><button class="close icon-cross4" data-dismiss="alert"></button>' . h($message) . '</div>';
		}

		Phpr::$session->flash->now();

		return $result;
	}
	
}






