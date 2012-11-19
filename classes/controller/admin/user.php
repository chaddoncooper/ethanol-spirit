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

		echo \Html::anchor('ethanol/admin/index', 'Back to the index');
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
		$user = \Ethanol\Ethanol::instance()->get_user($id);

		$fieldset = \Fieldset::forge();

		foreach (\Ethanol\Ethanol::instance()->group_list() as $group)
		{
			$attributes = array(
				'type' => 'checkbox',
				'value' => $group->id,
			);

			if (array_key_exists($group->id, $user->groups))
			{
				$attributes['checked'] = 'checked';
			}

			$fieldset->add('group[' . $group->id . ']', $group->name, $attributes);
		}

		$fieldset->add('submit', '', array('type' => 'submit', 'value' => 'save'));

		if ($fieldset->validation()->run())
		{
			$fields = $fieldset->validated();
			
			\Ethanol\Ethanol::instance()->set_user_groups($user, $fields['group']);
		}
		else if (count($fieldset->error()) > 0)
		{
			echo 'There was an error!<br />';
			echo $fieldset->show_errors();
		}

		$fieldset->repopulate();

		echo \Html::anchor('ethanol/admin/user', 'Back to the list');
		return \Response::forge($fieldset->build());
	}

}
