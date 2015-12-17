<?

	class FlynsarmyRedirects_URLTest extends Core_Configuration_Model
	{
		public $record_code = 'flynsarmyredirects_configuration';

		public static function create()
		{
			$obj = new self();
			return $obj->load();
		}

		public function build_form()
		{
			$this->add_field('url', 'URL', 'full', db_text)->renderAs(frm_text)->comment('See which (if any) redirects match the above URL');
		}
	}

?>