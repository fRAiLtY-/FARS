<?php

class Fars_IdCheck extends Db_ActiveRecord
{	
	public $table_name = 'fars_numbers';

	public static function create()
	{
		return new self();
	}
}