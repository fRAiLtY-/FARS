<?php

class Fars_Actions extends Cms_ActionScope
{
	public function login()
		{
			if (post('signup'))
				$this->on_signup();
			elseif (post('login'))
				$this->exec_action_handler('shop:on_login');
		}
	
	// check fars id
	public function on_signup()
	{
		if (post('flash'))
			Phpr::$session->flash['success'] = post('flash');
		
		$validation = new Phpr_Validation();

		$member = new Shop_Customer();
		$member->disable_column_cache('front_end', false);
		
		$member->init_columns_info('front_end');
		$member->validation->focusPrefix = null;
		$member->validation->getRule('email')->focusId('signup_email');
		
		if (!array_key_exists('password', $_POST))
			$member->generate_password();
			
		$check = Fars_IdCheck::create()->find_by_number(post('fars_number'));
		
		if($check) {
			$member->x_fars_number = post('fars_number');
			$member->save($_POST);
			$member->send_registration_confirmation();
			
			$redirect = post('redirect');
			if ($redirect)
				Phpr::$response->redirect($redirect);
		} else {
			$validation->setError("FARS number not valid", 'fars_number', true);
		}
			
		if (post('customer_auto_login'))
			Phpr::$frontend_security->customerLogin($member->id);

		if (!Phpr::$frontend_security->login($validation, $redirect, post('email'), post('password')))
		{
			$validation->add('email')->focusId('login_email');
			$validation->setError( "Invalid email or password.", 'email', true );
		}
	}
}