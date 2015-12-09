<?php

class Test_Result extends Db_ActiveRecord
{	
	public $table_name = 'grade_tests';

	public static function create()
	{
		return new self();
	}
}