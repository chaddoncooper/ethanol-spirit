<?php

namespace Ethanol;

class Controller_Admin_Index extends Controller_Admin_Base
{
	public function action_index()
	{
		echo \Html::anchor('ethanol/admin/group', 'Groups').'<br />';
		echo \Html::anchor('ethanol/admin/user', 'Users').'<br />';
		
		return \Response::forge();
	}
}
