<?php

class Fars_Module extends Core_ModuleBase
{

	/**
	 * Generate the module information
	 *
	 * @access protected
	 * @return Core_ModuleInfo
	 */

	protected function createModuleInfo()
	{
		return new Core_ModuleInfo(
			'FARS',
			'Base module for all FARS stuff',
			'Tom Davison'
		);
	}
	
	public function subscribeEvents()
	{
		Backend::$events->addEvent('shop:onExtendCustomerModel', $this, 'extend_customer_model');
		Backend::$events->addEvent('shop:onExtendCustomerForm', $this, 'extend_customer_form');
		Backend::$events->addEvent('shop:onGetCustomerFieldOptions', $this, 'get_customer_field_options');
	}
	
	public function extend_customer_model($customer)
	{
		$customer->define_column('x_fars_number', 'Fars Number');
		$customer->define_column('x_userlevel', 'User level');
		
	}

	public function extend_customer_form($customer, $context)
	{
		$customer->add_form_field('x_userlevel', 'left')->tab('FARS')->renderAs(frm_dropdown);
		$customer->add_form_field('x_fars_number', 'right')->tab('FARS')->comment('Can only be changed by a database administrator')->disabled();
		// skater, coach, club official, parent/guardian
	}
	
	public function get_customer_field_options($field_name, $current_key_value)
	{
		if ($field_name == 'x_userlevel')
		{
			$options = array(
				0 => 'Skater',
				1 => 'Coach',
				2 => 'Club Official',
				3 => 'Parent/Guardian'
			);
		 
			if ($current_key_value == -1)
				return $options;
		 
			if (array_key_exists($current_key_value, $options))
				return $options[$current_key_value];
		}
	}
}