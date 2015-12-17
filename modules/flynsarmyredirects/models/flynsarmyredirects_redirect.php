<?php

	class FlynsarmyRedirects_Redirect extends Db_ActiveRecord
	{
		public $table_name = 'flynsarmyredirects_redirects';

		public $implement = 'Db_Sortable';

		public $belongs_to = array(
			'group'=>array('class_name'=>'FlynsarmyRedirects_Group', 'foreign_key'=>'group_id')
		);

		public static $MATCH_TYPE_EXACT = 'Matches exactly';
		public static $MATCH_TYPE_STARTSWITH = 'Starts with';
		public static $MATCH_TYPE_ENDSWITH = 'Ends with';
		public static $MATCH_TYPE_REGEX = 'Regex';

		public static $REDIRECT_CODE_PERMANENT = 301;
		public static $REDIRECT_CODE_TEMPORARY = 302;

		public static function create($values = null)
		{
			return new self($values);
		}

		public function define_columns($context = null)
		{
			//$this->define_relation_column('group_id', 'group', 'Group', db_varchar, '@name')->invisible();
			$this->define_column('match_type', 'Match Type', db_varchar)->validation()->required();
			$this->define_column('from_url', 'From URL', db_varchar)->validation()->required();
			$this->define_column('to_url', 'To URL', db_varchar)->validation()->required();
			$this->define_column('redirect_code', 'Redirect Type', db_varchar)->validation()->required()->fn('intval');
			$this->define_column('hits', 'Hits', db_number)->validation()->fn('intval');
			$this->define_column('sort_order', 'Sort Order', db_number)->validation()->fn('intval');
		}

		public function define_form_fields($context = null)
		{
			$this->add_form_field('match_type')->renderAs(frm_dropdown);
			$this->add_form_field('from_url')->renderAs(frm_text)->comment('eg http://yoursite.com/contact/?foo=bar')->validation()->required();
			$this->add_form_field('to_url')->renderAs(frm_text)->validation()->required();
			$this->add_form_field('redirect_code', 'Redirect Type', 'full', db_varchar)->renderAs(frm_dropdown)->validation()->required();
			if ( !$this->new_record )
				$this->add_form_field('hits')->renderAs(frm_text)->validation()->fn('intval');
		}

		public function get_match_type_options($key_value = -1)
		{
			return array(
				self::$MATCH_TYPE_EXACT => 'Matches exactly',
				self::$MATCH_TYPE_STARTSWITH => 'Starts with',
				self::$MATCH_TYPE_ENDSWITH => 'Ends with',
				self::$MATCH_TYPE_REGEX => 'Regex',
			);
		}

		public function get_redirect_code_options($key_value = -1)
		{
			$return = array(
				self::$REDIRECT_CODE_PERMANENT => 'Permanent',
				self::$REDIRECT_CODE_TEMPORARY => 'Temporary',
			);

			if ( $key_value != -1 )
				return isset($return[$key_value]) ? $return[$key_value] : null;

			return $return;
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
				Db_DbHelper::query('update flynsarmyredirects_redirects set sort_order=:sort_order where id=:id', array(
					'sort_order'=>$order,
					'id'=>$id
				));
			}
		}

		/*
		 * Find matching redirect(s)
		 */
		public static function find_matching_redirects( $url, $limit=0 )
		{
			$sql_limit = '';
			if ( $limit ) $sql_limit = 'LIMIT ' . intval($limit);

			return Db_DbHelper::queryArray("
				SELECT r.id, g.id AS group_id, g.name AS group_name, r.from_url, r.to_url, r.redirect_code, r.match_type
				FROM flynsarmyredirects_groups g
				JOIN flynsarmyredirects_redirects r ON r.group_id=g.id
				WHERE
					g.is_enabled=1 AND r.is_enabled=1 AND
					CASE r.match_type
						WHEN 'Starts with' THEN :url LIKE CONCAT(r.from_url,'%')
						WHEN 'Ends with' THEN :url LIKE CONCAT('%', r.from_url)
						WHEN 'Regex' THEN :url REGEXP REPLACE(r.from_url, '(?:', '(')
						ELSE :url = r.from_url
					END
				ORDER BY g.sort_order, r.sort_order
				$sql_limit
			", array('url'=>$url));
		}

		/*
		 * Automatic Events
		 */
		public function add_permanent_redirect( $from_url, $to_url )
		{
			$Config = FlynsarmyRedirects_Configuration::create();

			return self::create()->save(array(
				'is_enabled' => 1,
				'from_url' => $from_url,
				'to_url' => $to_url,
				'match_type' => FlynsarmyRedirects_Redirect::$MATCH_TYPE_EXACT,
				'redirect_code' => FlynsarmyRedirects_Redirect::$REDIRECT_CODE_PERMANENT,
				'group_id' => $Config->event_group,
			));
		}

	}

?>
