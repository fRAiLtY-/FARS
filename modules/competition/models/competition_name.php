<?php

class Competition_Name extends Db_ActiveRecord
{	
	public $table_name = 'competition_name';
	
	public $belongs_to = array(
		'location'=>array('class_name'=>'Shop_CountryState', 
		'foreign_key'=>'location_id'),
	);
		
	public static function create()
	{
		return new self();
	}

	public function define_columns($context = null)
	{
		$this->define_column('name', 'Name');
		$this->define_relation_column('location', 'location', 'Location ', db_varchar, '@name')->listTitle('Location');
		$this->define_column('date', 'Date');
	}

	public function define_form_fields($context = null)
	{
		$this->add_form_field('name')->tab('Main');
		$this->add_form_field('location', 'left')->tab('Main');
		$this->add_form_field('date', 'right')->tab('Main');
	}

	public function get_state_options($key_value = -1)
	{
		if ($key_value != -1)
		{
			$obj = Shop_CountryState::create()->find($key_value);
			return $obj ? $obj->name : null;
		}

		$states = Shop_CountryState::get_object_list(17);
		$result = array();
		foreach ($states as $state)
			$result[$state->id] = $state->name;
			
		return $result;
	}
}