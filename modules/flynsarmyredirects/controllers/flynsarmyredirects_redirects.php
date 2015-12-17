<?

	class FlynsarmyRedirects_Redirects extends Backend_Controller
	{
		public $implement = 'Db_ListBehavior, Db_FormBehavior';
		public $list_model_class = 'FlynsarmyRedirects_Group';
		public $list_record_url = null;

		public $form_preview_title = 'Competitions';
		public $form_create_title = 'New Competition';
		public $form_edit_title = 'Edit Competition';
		public $form_model_class = 'FlynsarmyRedirects_Group';
		public $form_not_found_message = 'Competition not found';
		public $form_redirect = null;
		public $form_edit_save_auto_timestamp = true;
		public $form_create_save_redirect = null;
		public $form_flash_id = 'form_flash';

		public $form_edit_save_flash = 'The competition has been successfully saved';
		public $form_create_save_flash = 'The competition has been successfully added';
		public $form_edit_delete_flash = 'The competition has been successfully deleted';

		protected $required_permissions = array('flynsarmyredirects:manage_redirects');
		public $enable_concurrency_locking = true;

		protected $globalHandlers = array(
			'onLoadRedirectForm',
			'onUpdateRedirectList',
			'onSaveRedirect',
			'onDeleteSelectedRedirects',
			'onSetRedirectOrders',
			'onSave',
		);

		public function __construct()
		{
			Backend::$events->fireEvent('flynsarmyredirects:onConfigureRedirectsPage', $this);

			parent::__construct();
			$this->app_tab = 'flynsarmyredirects';
			$this->app_module_name = 'Redirects';

			$this->csv_import_url = url('/flynsarmyredirects/redirects');

			$this->list_record_url = url('/flynsarmyredirects/redirects/edit/');
			$this->form_redirect = url('/flynsarmyredirects/redirects');
			$this->form_create_save_redirect = url('/flynsarmyredirects/redirects/edit/%s/'.uniqid());
			$this->app_page = 'redirects';
		}

		public function formAfterEditSave($model, $session_key)
		{
			$this->viewData['form_model'] = $model;
			$model->updated_user_name = $this->currentUser->name;

			$this->renderMultiple(array(
				'form_flash'=>flash(),
				'object-summary'=>'@_sidebar_summary'
			));

			return true;
		}

		public function listPrepareData()
		{
			if ( in_array(Phpr::$router->action, array('create', 'edit')) )
				return FlynsarmyRedirects_Redirect::create();

			return FlynsarmyRedirects_Group::create();
		}

		public function index()
		{
			$this->app_page_title = 'Redirect Groups';
		}

		public function index_onShowTestURLForm()
		{
			$this->viewData['form_model'] = FlynsarmyRedirects_URLTest::create();
			$this->renderPartial('test_url_form');
		}

		public function index_onTestURL()
		{
			$data = post('FlynsarmyRedirects_URLTest');

			$validation = new Phpr_Validation();
			$validation->add('url')->fn('trim')->required('Please enter a URL')->url();
			if( !$validation->validate($data) )
				$validation->throwException();

			$this->viewData['redirects'] =
				FlynsarmyRedirects_Redirect::find_matching_redirects( $data['url'], 1 );
			$this->renderPartial('test_url_matches_table');
		}

		protected function index_onDeleteSelected()
		{
			$items_processed = 0;
			$items_deleted = 0;

			$item_ids = post('list_ids', array());
			$this->viewData['list_checked_records'] = $item_ids;

			foreach ($item_ids as $item_id)
			{
				$item = null;
				try
				{
					$item = FlynsarmyRedirects_Group::create()->find($item_id);
					if (!$item)
						throw new Phpr_ApplicationException('Redirect Group with identifier '.$item_id.' not found.');

					$item->delete();
					$items_deleted++;
					$items_processed++;
				}
				catch (Exception $ex)
				{
					if (!$item)
						Phpr::$session->flash['error'] = $ex->getMessage();
					else
						Phpr::$session->flash['error'] = 'Error deleting redirect group "'.$item->name.'": '.$ex->getMessage();

					break;
				}
			}

			if ($items_processed)
			{
				$message = null;

				if ($items_deleted)
					$message = 'Redirect groups deleted: '.$items_deleted;

				Phpr::$session->flash['success'] = $message;
			}

			$this->renderPartial('templates_page_content');
		}

		protected function index_onRefresh()
		{
			$this->renderPartial('templates_page_content');
		}

		protected function onSave($id)
		{
			Phpr::$router->action == 'create' ? $this->create_onSave() : $this->edit_onSave($id);
		}

		public function formAfterCreateSave($page, $session_key)
		{
			if (post('create_close'))
				$this->form_create_save_redirect = url('/flynsarmyredirects/redirects').'?'.uniqid();
		}

		public function listGetRowClass($model)
		{
			if ( !($model instanceof FlynsarmyRedirects_Group) )
				return null;
		}

		/*
		 * Groups
		 */
		protected function onUpdateGroupList($parentId = null)
		{
			try
			{
				$this->renderPartial('group_list', array(
					'session_key'=>$this->formGetEditSessionKey(),
					'form_model' => $this->getModelObj($parentId),
				));
			}
			catch (Exception $ex)
			{
				Phpr::$response->ajaxReportException($ex, true, true);
			}
		}

		/*
		 * Redirects
		 */

		protected function onLoadRedirectForm()
		{
			try
			{
				$id = post('model_id');
				$form_model = $id ? FlynsarmyRedirects_Redirect::create()->find($id) : FlynsarmyRedirects_Redirect::create();
				if (!$form_model)
					throw new Phpr_ApplicationException('Redirect not found');

				if ( $form_model->new_record )
					$form_model->define_form_fields('add');
				else
				{
					$form_model->define_form_fields();
					$form_model->define_columns();
				}

				$this->viewData['form_model'] = $form_model;
				$this->viewData['session_key'] = post('edit_session_key');
				$this->viewData['trackTab'] = false;
			}
			catch (Exception $ex)
			{
				throw new Phpr_ApplicationException($ex->getMessage());
				$this->handlePageError($ex);
			}

			$this->renderPartial('redirect_form');
		}

		protected function onSaveRedirect($parentId = null)
		{
			try
			{
				$data = post('FlynsarmyRedirects_Redirect', array());
				$data['form_id'] = $parentId;

				$model = FlynsarmyRedirects_Redirect::create();
				$model->define_columns();

				$id = post('model_id');
				if ( $id )
				{
					$model = $model->find($id);
					if (!$model)
						throw new Phpr_ApplicationException('Redirect not found');
				}

				$form = $this->getModelObj($parentId);
				$model->save($data, post('session_key'));

				if (!$id)
					$form->redirects->add($model, post('session_key'));
			}
			catch (Exception $ex)
			{
				Phpr::$response->ajaxReportException($ex, true, true);
			}
		}

		protected function onUpdateRedirectList($parentId = null)
		{
			try
			{
				$this->renderPartial('redirect_list', array(
					'session_key'=>$this->formGetEditSessionKey(),
					'form_model' => $this->getModelObj($parentId),
				));
			}
			catch (Exception $ex)
			{
				Phpr::$response->ajaxReportException($ex, true, true);
			}
		}

		protected function edit_onSetRedirectEnableState($parentId = null)
		{
			$group = $this->getModelObj($parentId);
			$item_ids = post('list_ids', array());
			$item_ids = array_map('intval', $item_ids);
			$this->viewData['list_checked_records'] = $item_ids;

			Db_DbHelper::query("
				UPDATE flynsarmyredirects_redirects
				SET is_enabled=:is_enabled
				WHERE id IN (".implode(',', $item_ids).")
			", array('is_enabled' => post('enabled', 0) ? 1 : 0));

			$this->renderPartial('redirect_list', array(
				'session_key'=>$this->formGetEditSessionKey(),
				'form_model' => $group,
			));
		}

		protected function onDeleteSelectedRedirects($parentId = null)
		{
			$items_processed = 0;
			$items_deleted = 0;

			$group = $this->getModelObj($parentId);
			$item_ids = post('list_ids', array());
			$this->viewData['list_checked_records'] = $item_ids;

			foreach ($item_ids as $item_id)
			{
				$item = null;
				try
				{
					$item = FlynsarmyRedirects_Redirect::create()->find($item_id);
					if (!$item)
						throw new Phpr_ApplicationException('Redirect with identifier '.$item_id.' not found.');

					$group->redirects->delete($item, $this->formGetEditSessionKey());
					$item->delete();
					$items_deleted++;
					$items_processed++;
				}
				catch (Exception $ex)
				{
					if (!$item)
						Phpr::$session->flash['error'] = $ex->getMessage();
					else
						Phpr::$session->flash['error'] = 'Error deleting redirect with \'From URL\' "'.$item->from_url.'": '.$ex->getMessage();

					break;
				}
			}

			//if ($items_processed)
			//{
			//	$message = null;
			//
			//	if ($items_deleted)
			//		$message = 'Redirects deleted: '.$items_deleted;
			//
			//	Phpr::$session->flash['success'] = $message;
			//}

			$this->renderPartial('redirect_list', array(
				'session_key'=>$this->formGetEditSessionKey(),
				'form_model' => $group,
			));
		}

		/*
		 * Set orders
		 */
		protected function index_onSetGroupOrders($parentId = null)
		{
			try
			{
				FlynsarmyRedirects_Group::set_orders(post('item_ids'), post('sort_orders'));
			}
			catch (Exception $ex)
			{
				Phpr::$response->ajaxReportException($ex, true, true);
			}
		}

		protected function onSetRedirectOrders($parentId = null)
		{
			try
			{
				FlynsarmyRedirects_Redirect::set_orders(post('item_ids'), post('sort_orders'));
			}
			catch (Exception $ex)
			{
				Phpr::$response->ajaxReportException($ex, true, true);
			}
		}






		private function getModelObj($id)
		{
			return strlen($id) ? $this->formFindModelObject($id) : (new $this->form_model_class);
		}

		/*
		 * Import products
		 */

		public function import_csv()
		{
			$this->app_page_title = 'Import Redirects';
		}
	}

?>