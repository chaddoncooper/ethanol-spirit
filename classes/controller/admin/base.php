<?php

namespace Ethanol;

/**
 * Base controller to define common admin functionality. (Such as checking access
 * permissions)
 * 
 * @author Steve "uru" West <uruwolf@gmail.com>
 * @license http://philsturgeon.co.uk/code/dbad-license DbaD
 */
abstract class Controller_Admin_Base extends \Controller
{
	
	public function check_access($permission)
	{
		if(!\Ethanol\Ethanol::instance()->user_has_permission($permission))
		{
			throw new AccessDenied('GTFO!');
		}
	}
}

class AccessDenied extends \Exception{}
