<?php

	class FlynsarmyRedirects_Module extends Core_ModuleBase
	{
		public static $old_url = null;
		public static $debug = false;

		/**
		 * Creates the module information object
		 * @return Core_ModuleInfo
		 */
		protected function createModuleInfo()
		{
			return new Core_ModuleInfo(
				"Redirects",
				"Provides an easy way to redirect pages.",
				"Flynsarmy" );
		}

		/**
		 * Builds user permissions interface
		 * For drop-down and radio fields you should also add methods returning
		 * options. For example, of you want to have "Access Level" drop-down:
		 * public function get_access_level_options();
		 * This method should return array with keys corresponding your option identifiers
		 * and values corresponding its titles.
		 *
		 * @param $host_obj ActiveRecord object to add fields to
		 */
		public function buildPermissionsUi($host_obj)
		{
			$host_obj->add_field($this, 'manage_redirects', 'Manage redirects', 'left')->renderAs(frm_checkbox)->comment('Create, modify or delete redirect groups.', 'above');
			$host_obj->add_field($this, 'manage_configuration', 'Manage configuration', 'right')->renderAs(frm_checkbox)->comment('Update configuration options.', 'above');
			$host_obj->add_field($this, 'redirects_export_import', 'Export or import redirects', 'full')->renderAs(frm_checkbox)->comment('Export or import the redirect list from CSV files.', 'above');
		}

		/**
		 * Returns a list of the module back-end GUI tabs.
		 * @param Backend_TabCollection $tabCollection A tab collection object to populate.
		 * @return mixed
		 */
		public function listTabs($tabCollection)
		{
			$user = Phpr::$security->getUser();
			$tabs = array(
				'redirects'=>array('redirects', 'Manage', 'manage_redirects'),
				//'configurations'=>array('configurations', 'Configuration', 'manage_configuration'),
			);

			$first_tab = null;
			foreach ($tabs as $tab_id=>$tab_info)
			{
				if (($tabs[$tab_id][3] = $user->get_permission('flynsarmyredirects', $tab_info[2])) && !$first_tab)
					$first_tab = $tab_info[0];
			}

			if ($first_tab)
			{
				$tab = $tabCollection->tab('flynsarmyredirects', 'Competitions', $first_tab, 20);
				foreach ($tabs as $tab_id=>$tab_info)
				{
					if ($tab_info[3])
						$tab->addSecondLevel($tab_id, $tab_info[1], $tab_info[0]);
				}
			}
		}

		public function subscribeEvents()
		{
			Backend::$events->addEvent('cms:onBeforeRoute', $this, 'before_route');
			//Events
			Backend::$events->addEvent('core:onBeforeFormRecordUpdate', $this, 'before_form_record_update');
			Backend::$events->addEvent('core:onAfterFormRecordUpdate', $this, 'after_form_record_update');
		}

		public function before_route($url)
		{
			$url = FlynsarmyRedirects_Url_Helper::current();

			//We want to keep things fast do don't use Db_ActiveRecord
			$redirects = FlynsarmyRedirects_Redirect::find_matching_redirects( $url, 1 );

			// I stupidly left :443 on HTTPS urls. So also find redirects without :443
			if ( isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on" )
			{
				$replace_count = 1;
				$url = str_replace(':443', '', $url, $replace_count);
				$redirects = array_merge(
					$redirects,
					FlynsarmyRedirects_Redirect::find_matching_redirects( $url, 1 )
				);
			}

			self::debug("Found  " . sizeof($redirects) . " matches for URL " . $url . " : " . print_r($redirects, true));

			if ( sizeof($redirects) )
			{
				Db_DbHelper::query("
					UPDATE flynsarmyredirects_redirects
					SET hits = hits + 1
					WHERE id=:id
				", array('id' => $redirects[0]['id']));

				if ( $redirects[0]['match_type'] == 'Regex' )
				{
					$redirects[0]['to_url'] = preg_replace(
						"!".str_replace(array('!', '?'), array('\!', '\?'), $redirects[0]['from_url'])."!",
						$redirects[0]['to_url'],
						$url
					);

					self::debug("Match type is regex. To URL updated to  " . $redirects[0]['to_url']);
				}

				self::debug("Redirecting to ".$redirects[0]['to_url']." with code " . $redirects[0]['redirect_code']);

				header("Location: ".$redirects[0]['to_url'], true, $redirects[0]['redirect_code']);
				exit;
			}

			if ( !isset($_SERVER['HTTP_HOST']) )
			{
				self::debug("No HTTP_HOST server variable");

				return;
			}

			$is_www = substr($_SERVER['HTTP_HOST'], 0, 4) == 'www.';
			//Check www status
			//This is done after the above redirects because they'll often handle it for us
			$Config = FlynsarmyRedirects_Configuration::create();
			if ( $Config->www_redirect == 'www' && !$is_www )
			{
				self::debug("Redirecting to WWW");

				header("Location: ".FlynsarmyRedirects_Url_Helper::get_www(), true, 301);
				exit;
			}
			elseif ( $Config->www_redirect == 'non-www' && $is_www )
			{
				self::debug("Redirecting away from WWW");

				header("Location: ".FlynsarmyRedirects_Url_Helper::get_nonwww(), true, 301);
				exit;
			}
		}

		public function before_form_record_update(Backend_Controller $controller, Db_ActiveRecord $record)
		{
			$Config = FlynsarmyRedirects_Configuration::create();
			if ( !$Config->event_group )
				return;

			if ( $record instanceof Shop_Product )
			{
				if ( !$Config->event_changed_product_url ) return;

				self::$old_url = root_url($record->page_url(FlynsarmyRedirects_Url_Helper::get_product_page()), true);
			}
			elseif ( $record instanceof Shop_Category )
			{
				if ( !$Config->event_changed_category_url ) return;

				self::$old_url = root_url($record->page_url(FlynsarmyRedirects_Url_Helper::get_category_page()), true);
			}
			elseif ( $record instanceof Blog_Category )
			{
				if ( !$Config->event_changed_blog_category_url ) return;

				self::$old_url = root_url(FlynsarmyRedirects_Url_Helper::get_blog_category_page(), true) .
					'/' . $record->url_name;
			}
			elseif ( $record instanceof Blog_Post )
			{
				if ( !$Config->event_changed_blog_post_url ) return;

				self::$old_url = root_url(FlynsarmyRedirects_Url_Helper::get_blog_post_page(), true) .
					'/' . $record->url_title;
			}
			elseif ( $record instanceof Cms_Page )
			{
				if ( !$Config->event_changed_page_url ) return;

				self::$old_url = root_url($record->url, true);
			}
		}

		public function after_form_record_update(Backend_Controller $controller, Db_ActiveRecord $record)
		{
			$Config = FlynsarmyRedirects_Configuration::create();

			if ( !self::$old_url || !$Config->event_group )
				return;

			$new_url = '';

			if ( $record instanceof Shop_Product )
			{
				if ( !$Config->event_changed_product_url ) return;

				$new_url = root_url($record->page_url(FlynsarmyRedirects_Url_Helper::get_product_page()), true);
			}
			elseif ( $record instanceof Shop_Category )
			{
				if ( !$Config->event_changed_category_url ) return;

				$new_url = root_url($record->page_url(FlynsarmyRedirects_Url_Helper::get_category_page()), true);
			}
			elseif ( $record instanceof Blog_Category )
			{
				if ( !$Config->event_changed_blog_category_url ) return;

				$new_url = root_url(FlynsarmyRedirects_Url_Helper::get_blog_category_page(), true) .
					'/' . $record->url_name;
			}
			elseif ( $record instanceof Blog_Post )
			{
				if ( !$Config->event_changed_blog_post_url ) return;

				$new_url = root_url(FlynsarmyRedirects_Url_Helper::get_blog_post_page(), true) .
					'/' . $record->url_title;
			}
			elseif ( $record instanceof Cms_Page )
			{
				if ( !$Config->event_changed_page_url ) return;

				$new_url = root_url($record->url, true);
			}

			//Only create a redirect if the URL has been changed
			if ( $new_url && self::$old_url != $new_url )
			{
				FlynsarmyRedirects_Redirect::create()
					->add_permanent_redirect(self::$old_url, $new_url);
				// Empty the old URL incase we're saving anything else at the same time
				self::$old_url = null;
			}

		}

		public static function debug( $message )
		{
			if ( self::$debug )
				traceLog('FlynsarmyRedirects: ' . $message);

			return false;
		}
	}
?>