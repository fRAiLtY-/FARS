<?php

class Test_Grade extends Db_ActiveRecord
{	
	public $table_name = 'grade_names';

	public static function create()
	{
		return new self();
	}
}