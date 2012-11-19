<?php

namespace Ethanol;

/**
 * Allows groups to be listed/added/deleted
 * 
 * @author Steve "uru" West <uruwolf@gmail.com>
 * @license http://philsturgeon.co.uk/code/dbad-license DbaD
 */
class Controller_Admin_Group extends Controller_Admin_Base
{

	public function action_index()
	{
		$groups = \Ethanol\Ethanol::instance()->group_list();
		
		echo \Html::anchor('ethanol/admin/group/add', 'Add Group').'<ul>';
		foreach($groups as $group)
		{
			echo '<li>';
			echo \Html::anchor('ethanol/admin/group/delete/'.$group->id, 'Delete');
			echo ' ';
			echo \Html::anchor('ethanol/admin/group/edit/'.$group->id, 'Edit');
			echo ' ';
			echo $group->name;
			echo '</li>';
		}
		echo '</ul>';
		
		return \Response::forge();
	}

	/**
	 * Allows new groups to be added
	 */
	public function action_add()
	{
		$fieldset = \Fieldset::forge();

		$fieldset->add('name', 'Name', array(), array(
			'required',
			array('max_length', array(100)),
		));
		$fieldset->add('submit', '', array('type' => 'submit', 'value' => 'Add'));

		if ($fieldset->validation()->run())
		{
			$fields = $fieldset->validated();

			/**
			 * This is the part that actually adds the group
			 * ************************************************************** */
			try
			{
				\Ethanol\Ethanol::instance()->add_group($fields['name']);
				echo 'The group, '.$fields['name'].', was added.<br />';
			}
			catch (\Ethanol\ColumnNotUnique $exc)
			{
				echo 'The group name "'.$fields['name'].'" is already in use!<br />';
			}
		}
		else if (count($fieldset->error()) > 0)
		{
			echo 'There was an error!<br />';
			echo $fieldset->show_errors();
		}

		$fieldset->repopulate();

		echo \Html::anchor('ethanol/admin/group', 'Back to the list').'<br />';
		return \Response::forge($fieldset->build());
	}

	public function action_delete($id)
	{
		\Ethanol\Ethanol::instance()->delete_group($id);
		\Response::redirect('ethanol/admin/group');
	}
	
	public function action_edit($id)
	{
		$group = \Ethanol\Ethanol::instance()->get_group($id);
		
		$fieldset = \Fieldset::forge();

		$fieldset->add('name', 'Name', array(), array(
			'required',
			array('max_length', array(100)),
		));
		$fieldset->add('submit', '', array('type' => 'submit', 'value' => 'Save'));

		if ($fieldset->validation()->run())
		{
			$fields = $fieldset->validated();

			/**
			 * This is the part that actually saves the group
			 * ************************************************************** */
			try
			{
				//You can pass just an ID but passing the group object removes
				//the need for an extra query
				\Ethanol\Ethanol::instance()->update_group($group, $fields['name']);
				echo 'The group, '.$fields['name'].', was saved.<br />';
			}
			catch (\Ethanol\ColumnNotUnique $exc)
			{
				echo 'The group name "'.$fields['name'].'" is already in use!<br />';
			}
		}
		else if (count($fieldset->error()) > 0)
		{
			echo 'There was an error!<br />';
			echo $fieldset->show_errors();
		}

		$fieldset->populate($group, true);

		echo \Html::anchor('ethanol/admin/group', 'Back to the list').'<br />';
		return \Response::forge($fieldset->build());
	}

}
