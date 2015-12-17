<?

	class FlynsarmyRedirects_Configurations extends Backend_Controller
	{
		public $implement = 'Db_FormBehavior';

		public $app_tab = 'flynsarmyredirects';
		public $app_page = 'configurations';
		public $app_module_name;

		public $form_edit_title = 'Configuraton';
		public $form_model_class = 'FlynsarmyRedirects_Configuration';
		public $form_redirect = null;

		protected $required_permissions = array('flynsarmyredirects:manage_configuration');

		public function __construct()
		{
			parent::__construct();
		}

		public function index()
		{
			$this->app_page_title = 'Configuration';

			try
			{
				$obj = FlynsarmyRedirects_Configuration::create();
				$this->viewData['form_model'] = $obj;
			}
			catch (exception $ex)
			{
				$this->_controller->handlePageError($ex);
			}
		}

		protected function index_onSave()
		{
			try
			{
				$obj = FlynsarmyRedirects_Configuration::create();
				$obj->save(post($this->form_model_class, array()));

				echo Backend_Html::flash_message('Configuration saved.');
			}
			catch (Exception $ex)
			{
				Phpr::$response->ajaxReportException($ex, true, true);
			}
		}
	}

?>