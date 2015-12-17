<?

	class FlynsarmyRedirects_Dropdowns_Helper
	{
		public static $cache = array();

		public static function get_products()
		{
			if ( isset(self::$cache['products']) )
				return self::$cache['products'];

			$return = array();
			$options = Db_DbHelper::objectArray('select id, name from shop_products where product_id IS NULL order by name');
			foreach ( $options as $option )
				$return[ $option->id ] = $option->name;

			return (self::$cache['products']=$return);
		}

		public static function get_pages()
		{
			if ( isset(self::$cache['pages']) )
				return self::$cache['pages'];

			//Only grab pages for current theme
			$where = "";
			if (Cms_Theme::is_theming_enabled() && ($theme = Cms_Theme::get_edit_theme()))
				$where = "WHERE theme_id=".$theme->id;

			$return = array();
			$options = Db_DbHelper::objectArray("select id, CONCAT(title, ' (', url, ')') AS title from pages $where order by title");
			foreach ( $options as $option )
				$return[ $option->id ] = $option->title;

			return (self::$cache['pages']=$return);
		}

		public static function get_categories()
		{
			if ( isset(self::$cache['categories']) )
				return self::$cache['categories'];

			$return = array();
			self::get_category_children_recursive(null, $return, 0, null);
			return (self::$cache['categories']=$return);


			// if ( isset(self::$cache['categories']) )
			// 	return self::$cache['categories'];

			// $return = array();
			// $options = Db_DbHelper::objectArray('select id, name from shop_categories where category_id IS NULL order by name');
			// foreach ( $options as $option )
			// 	$return[ $option->id ] = $option->name;

			// return (self::$cache['categories']=$return);
		}

		//Used by get_categories() to recursively grab a nested category drop down list
		protected static function get_category_children_recursive($items, &$result, $level, $ignore)
		{
			if ($items === null)
				$items = Shop_Category::list_children_category_proxies(null);

			foreach ($items as $item)
			{
				if ($ignore !== null && $item->id == $ignore)
					continue;

				$str = '';
				for ( $i=0; $i<$level; $i++ )
					$str .= '- ';

				$result[$item->id] = $str . $item->name;
				self::get_category_children_recursive(Shop_Category::list_children_category_proxies($item->id), $result, $level+1, $ignore);
			}
		}

		public static function get_blog_categories()
		{
			if ( isset(self::$cache['blog_categories']) )
				return self::$cache['blog_categories'];

			$return = array();
			$options = Db_DbHelper::objectArray('select id, CONCAT(name, " (/", url_name, ")") AS name from blog_categories order by name');
			foreach ( $options as $option )
				$return[ $option->id ] = $option->name;

			return (self::$cache['blog_categories']=$return);
		}

		public static function get_blog_posts()
		{
			if ( isset(self::$cache['blog_posts']) )
				return self::$cache['blog_posts'];

			$return = array();
			$options = Db_DbHelper::objectArray('select id, title from blog_posts WHERE is_published IS NOT NULL AND published_date IS NOT NULL order by published_date DESC, created_at DESC');
			foreach ( $options as $option )
				$return[ $option->id ] = $option->title;

			return (self::$cache['blog_posts']=$return);
		}
	}

?>