<?php

	class FlynsarmyRedirects_Group extends Core_Configuration_Model
	{
		public $table_name = 'flynsarmyredirects_groups';

		public $implement = 'Db_Sortable, Db_AutoFootprints';
		public $auto_footprints_visible = true;
		
		public $belongs_to = array(
			'location'=>array('class_name'=>'Shop_CountryState', 
			'foreign_key'=>'location_id'),
		);

		public $has_many = array(
			'redirects'=>array(
				'class_name'=>'FlynsarmyRedirects_Redirect', 
				'foreign_key'=>'group_id', 
				'order'=>'sort_order, id', 
				'delete'=>true
			)
		);

/*
		public $calculated_columns = array(
			'redirect_count'=>array(
				'sql'=>"(select count(*) from flynsarmyredirects_redirects WHEREflynsarmyredirects_redirects.group_id=flynsarmyredirects_groups.id)", 'type'=>db_number),
			'hit_count'=>array(
				'sql'=>"(select SUM(hits) from flynsarmyredirects_redirects WHERE flynsarmyredirects_redirects.group_id=flynsarmyredirects_groups.id)", 'type'=>db_number
			),
		);
*/

		public static function create()
		{
			return new self();
		}

		public function define_columns($context = null)
		{
			$this->define_column('name', 'Name', db_varchar)->order('asc')->validation()->fn('trim')->required("Please specify the competition name.")->unique();
			$this->define_column('short_description', 'Short Description', db_varchar)->validation()->fn('trim');

			$this->define_multi_relation_column('redirects', 'redirects', 'Redirects', '@from_url')->invisible();
			$this->define_column('date', 'Date', 'Competition Date')->validation()->required('Please select a date');
			$this->define_relation_column('location', 'location', 'Location ', db_varchar, '@name')->listTitle('Where')->validation()->required('Please select an area');
			$this->define_column('redirect_count', 'Redirects');
			$this->define_column('hit_count', 'Hits');
		}

		public function define_form_fields($context = null)
		{
			$this->add_field('name', 'Field Name', 'left', db_varchar)->tab('Competition')->validation()->required();
			$this->add_field('short_description', 'Short Description', 'right', db_varchar)->tab('Competition');
			$this->add_form_field('date', 'left')->renderAs(frm_date)->tab('Competition');
			$this->add_form_field('location', 'right')->renderAs(frm_dropdown)->tab('Competition');
			$this->add_form_field('redirects')->tab('Categories')->renderAs('redirects')->comment('Drag and drop the redirects below to sort them.', 'above')->noLabel();
			$this->add_form_field('redirects')->tab('Events')->renderAs('redirects')->comment('Drag and drop the redirects below to sort them.', 'above')->noLabel();
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

		public static function set_orders($item_ids, $item_orders)
		{
			if (is_string($item_ids))
				$item_ids = explode(',', $item_ids);

			if (is_string($item_orders))
				$item_orders = explode(',', $item_orders);

			foreach ($item_ids as $index=>$id)
			{
				$order = $item_orders[$index];
				Db_DbHelper::query('update flynsarmyredirects_groups set sort_order=:sort_order where id=:id', array(
					'sort_order'=>$order,
					'id'=>$id
				));
			}
		}
	}

?>
