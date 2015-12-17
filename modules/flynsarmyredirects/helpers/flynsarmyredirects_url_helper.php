<?php
	class FlynsarmyRedirects_Url_Helper
	{
		public static $url_cache = array();

		/*
		 * Returns the category page URL relative to site_url()
		 */
		public static function get_category_page()
		{
			if ( isset(self::$url_cache['category_page']) )
				return self::$url_cache['category_page'];

			$Config = FlynsarmyRedirects_Configuration::create()->load();
			if ( $Config->category_page_id )
				return (self::$url_cache['category_page'] = Cms_Page::create()->find($Config->category_page_id)->url);

			return '/category';
		}

		/*
		 * Returns the product page URL relative to site_url()
		 */
		public static function get_product_page()
		{
			if ( isset(self::$url_cache['product_page']) )
				return self::$url_cache['product_page'];

			$Config = FlynsarmyRedirects_Configuration::create()->load();
			if ( $Config->product_page_id )
				return (self::$url_cache['product_page'] = Cms_Page::create()->find($Config->product_page_id)->url);

			return '/product';
		}

		/*
		 * Returns the blog category page URL relative to site_url()
		 */
		public static function get_blog_category_page()
		{
			if ( isset(self::$url_cache['blog_category_page']) )
				return self::$url_cache['blog_category_page'];

			$Config = FlynsarmyRedirects_Configuration::create()->load();
			if ( $Config->blog_category_page_id )
				return (self::$url_cache['blog_category_page'] = Cms_Page::create()->find($Config->blog_category_page_id)->url);

			return '/blog';
		}

		/*
		 * Returns the blog post page URL relative to site_url()
		 */
		public static function get_blog_post_page()
		{
			if ( isset(self::$url_cache['blog_post_page']) )
				return self::$url_cache['blog_post_page'];

			$Config = FlynsarmyRedirects_Configuration::create()->load();
			if ( $Config->blog_post_page_id )
				return (self::$url_cache['blog_post_page'] = Cms_Page::create()->find($Config->blog_post_page_id)->url);

			return '/blog/post';
		}

		public static function current( $include_querystring = true )
		{
			$pageURL = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") ? "https://" : "http://";
			$pageURL .= $_SERVER["SERVER_NAME"];
			if ($_SERVER["SERVER_PORT"] != "80")
				$pageURL .= ":".$_SERVER["SERVER_PORT"];

			if ( $include_querystring )
				$pageURL .= urldecode($_SERVER["REQUEST_URI"]);

			return $pageURL;
		}

		public static function get_www( $include_querystring = true )
		{
			$pageURL = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") ? "https://" : "http://";
			$pageURL .= 'www.'.$_SERVER["SERVER_NAME"];
			if ($_SERVER["SERVER_PORT"] != "80")
				$pageURL .= ":".$_SERVER["SERVER_PORT"];

			if ( $include_querystring )
				$pageURL .= $_SERVER["REQUEST_URI"];

			return $pageURL;
		}

		public static function get_nonwww( $include_querystring = true )
		{
			$pageURL = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") ? "https://" : "http://";
			$pageURL .= str_replace('www.', '', $_SERVER["SERVER_NAME"]);
			if ($_SERVER["SERVER_PORT"] != "80")
				$pageURL .= ":".$_SERVER["SERVER_PORT"];

			if ( $include_querystring )
				$pageURL .= $_SERVER["REQUEST_URI"];

			return $pageURL;
		}
	}