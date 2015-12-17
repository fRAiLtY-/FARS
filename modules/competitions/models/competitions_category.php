<?php

class Competitions_Category extends Db_ActiveRecord
{	
	public $table_name = 'competitions_categories';
	
	public $belongs_to = array(
		'competitions'=>array('class_name'=>'Competitions_Competition', 
		'foreign_key'=>'competition_id'),
	);
		
	public static function create()
	{
		return new self();
	}

	public function define_columns($context = null)
	{
		$this->define_column('name', 'Name');
		$this->define_relation_column('competition_id', 'competitions', 'Assign to a Competition', db_varchar, '@name')->listTitle('Competition');
	}

	public function define_form_fields($context = null)
	{
		$this->add_form_field('name')->tab('Category');
		$this->add_form_field('competition_id')->tab('Category');
	}
}