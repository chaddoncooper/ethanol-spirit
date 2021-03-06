<?php

namespace Ethanol;

/**
 * 
 * @author Steve "uru" West <uruwolf@gmail.com>
 * @license http://philsturgeon.co.uk/code/dbad-license DbaD
 */
class Controller_Account extends \Controller
{
	
	public function action_test()
	{
		var_dump(\Ethanol\Ethanol::instance()->logged_in());
		echo '<pre>';
		print_r(\Ethanol\Ethanol::instance()->current_user());
		exit;
	}
	
	/**
	 * Logs a user out.
	 */
	public function action_log_out()
	{
		\Ethanol\Ethanol::instance()->log_out();
		
		return \Response::forge('You have been logged out');
	}
	
	/**
	 * Tries to log the user in
	 */
	public function action_log_in()
	{
		$input = \Input::all();
		
		if(isset($input['driver']))
		{
			echo 'User logged in:<pre>';
			//Make the call to Ethanol to try a login
			print_r(\Ethanol\Ethanol::instance()->log_in($input));
			exit;
		}
		
		$forms = \Ethanol\Ethanol::instance()->get_form();
		return implode('<br />', $forms);
	}

	/**
	 * Creates a new user
	 */
	public function action_create()
	{
		//Build a fieldset and add some basic validation
		$fieldset = \Fieldset::forge();

		$fieldset->add('email', 'Email', array(), array(
			'required',
			array('max_length', array(100)),
			'valid_email',
		));
		$fieldset->add('password', 'Password', array('type' => 'password'), array(
			'required',
			array('max_length', array(100)),
		));
		$fieldset->add('password2', 'Password again', array('type' => 'password'), array(
			'required',
			array('max_length', array(100)),
			array('match_field', 'password'),
		));

		$fieldset->add('submit', '', array('type' => 'submit', 'value' => 'Create'));

		//Check to see if the form has been submitted
		if ($fieldset->validation()->run())
		{
			//If so get the data and ask Ethanol to create a new user
			$fields = $fieldset->validated();

			/**
			 * This is the part that actually saves the new user!             *
			 * ************************************************************** */
			$userData = array(
				'password' => $fields['password'],
				'email' => $fields['email'],
			);
			echo '<pre>';
			print_r(\Ethanol\Ethanol::instance()->create_user($userData));
			exit;
		}
		else if (count($fieldset->error()) > 0)
		{
			//There where some errors so show them
			echo 'There was an error!<br />';
			echo $fieldset->show_errors();
		}

		$fieldset->repopulate();

		return \Response::forge($fieldset->build());
	}

	/**
	 * Allows a user to activate their email address
	 */
	public function action_activate($key)
	{
		try
		{
			\Ethanol\Ethanol::instance()->activate($key);
			echo 'Your account has been activated and you can now log in.';
		}
		catch (Exception $exc)
		{
			echo $exc;
		}
		
		return \Response::forge();
	}

}
