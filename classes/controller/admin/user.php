<?php

namespace Ethanol;

/**
 * Contains various actions to do with managing users
 * 
 * @author Steve "uru" West
 * @license http://philsturgeon.co.uk/code/dbad-license DbaD
 */
class Controller_Admin_User extends Controller_Admin_Base
{

	/**
	 * Shows a list of users
	 */
	public function action_index()
	{
		$users = \Ethanol\Ethanol::instance()->get_users();

		echo '<ul>';
		foreach ($users as $user)
		{
			echo '<li>';
			echo \Html::anchor('ethanol/admin/user/groups/' . $user->id, $user->email);
			echo '</li>';
		}
		echo '</ul>';

		return \Response::forge();
	}

	/**
	 * Allows a user to be added or removed from groups
	 */
	public function action_groups($id)
	{
		$fieldset = \Fieldset::forge();

		foreach (\Ethanol\Ethanol::instance()->group_list() as $group)
		{
			$fieldset->add('group[' . $group->id . ']', $group->name, array('type' => 'checkbox', 'value' => '1'));
		}

		$fieldset->add('submit', '', array('type' => 'submit', 'value' => 'save'));

		echo \Html::anchor('ethanol/admin/user', 'Back to the list');
		return \Response::forge($fieldset->build());
	}

}
