<?php

class Test_Module extends Core_ModuleBase
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
			'Tests',
			'Test results and gradings',
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
		$customer->define_column('x_grade', 'Current grade');
		
	}

	public function extend_customer_form($customer, $context)
	{
		$customer->add_form_field('x_grade', 'left')->tab('FARS')->renderAs(frm_dropdown);
		// skater, coach, club official, parent/guardian
	}
	
	public function get_customer_field_options($field_name, $current_key_value)
	{
		if ($field_name == 'x_grade')
		{
			$options = Test_Grade::create()->collection()->as_array('grade', 'id');
		 
			if ($current_key_value == -1)
				return $options;
		 
			if (array_key_exists($current_key_value, $options))
				return $options[$current_key_value];
		}
	}
}