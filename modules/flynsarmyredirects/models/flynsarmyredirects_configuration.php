<?

	class FlynsarmyRedirects_Configuration extends Core_Configuration_Model
	{
		public $record_code = 'flynsarmyredirects_configuration';

		public static function create()
		{
			$obj = new self();
			return $obj->load();
		}

		public function build_form()
		{
			$this->add_field('www_redirect', 'WWW Redirect', 'full', db_text)->tab('General')->renderAs(frm_dropdown)->comment('Redirecting to either WWW or Non-WWW is recommended for increased SEO.');
			$this->add_field('category_page_id', 'Category Page', 'left', db_number)->tab('Events')->renderAs(frm_dropdown)->optionsMethod('get_page_list_options');
			$this->add_field('product_page_id', 'Product Page', 'right', db_number)->tab('Events')->renderAs(frm_dropdown)->optionsMethod('get_page_list_options');
			$this->add_field('blog_category_page_id', 'Blog Category Page', 'left', db_number)->tab('Events')->renderAs(frm_dropdown)->optionsMethod('get_page_list_options');
			$this->add_field('blog_post_page_id', 'Blog Post Page', 'right', db_number)->tab('Events')->renderAs(frm_dropdown)->optionsMethod('get_page_list_options');
			$this->add_field('event_group', 'Group', 'full', db_text)->tab('Events')->renderAs(frm_dropdown)->comment("All redirects generated will be placed into this redirect group");
			$this->add_field('event_changed_category_url', 'Category URL Change', 'left', db_bool)->tab('Events')->renderAs(frm_checkbox)->comment("Add a permanent redirect from the old category URL to the new");
			$this->add_field('event_changed_product_url', 'Product URL Change', 'right', db_bool)->tab('Events')->renderAs(frm_checkbox)->comment("Add a permanent redirect from the old product URL to the new");
			$this->add_field('event_changed_blog_category_url', 'Blog Category URL Change', 'left', db_bool)->tab('Events')->renderAs(frm_checkbox)->comment("Add a permanent redirect from the old blog category URL to the new");
			$this->add_field('event_changed_blog_post_url', 'Blog Post URL Change', 'right', db_bool)->tab('Events')->renderAs(frm_checkbox)->comment("Add a permanent redirect from the old blog post URL to the new");
			$this->add_field('event_changed_page_url', 'Page URL Change', 'full', db_bool)->tab('Events')->renderAs(frm_checkbox)->comment("Add a permanent redirect from the old page URL to the new");
		}

		public function get_www_redirect_options($keyValue =- 1)
		{
			return array(
				'either' => 'Either',
				'www' => 'Non-WWW to WWW',
				'non-www' => 'WWW to Non-WWW'
			);
		}

		public function get_page_list_options($keyvalue = -1)
		{
			return FlynsarmyRedirects_Dropdowns_Helper::get_pages();
		}

		public function get_event_group_options($keyValue =- 1)
		{
			$groups = FlynsarmyRedirects_Group::create()->find_all();
			$return = array();

			foreach ( $groups as $group )
				$return[ $group->id ] = $group->name;

			return $return;
		}
	}

?>