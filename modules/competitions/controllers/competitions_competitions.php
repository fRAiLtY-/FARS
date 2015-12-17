<?php

class Competitions_Competitions extends Backend_Controller
{
	public $implement = 'Db_ListBehavior, Db_FormBehavior, Db_FilterBehavior';
	public $list_model_class = 'Competitions_Competition';
	public $list_record_url = null;
	public $form_redirect = null;
	
	public $app_tab = 'competitions';
	public $app_module_name = 'Competitions';
	
	public $form_model_class = 'Competitions_Competition';	
	public $form_create_title = 'Create competition';
	public $form_edit_title = 'Edit competition';
	public $form_not_found_message = 'Competition not found';
	
	public $form_edit_save_flash = 'The competition has been successfully saved';
	public $form_create_save_flash = 'The competition has been successfully created';
	public $form_edit_delete_flash = 'The competition has been successfully deleted';

	public $list_search_enabled = true;
	public $list_search_fields = array('name', 'location');
	public $list_search_prompt = 'Find competitions by name or location';
	
	public function __construct()
	{
		parent::__construct();
		$this->app_page = 'Competitions';
		
		$this->list_record_url = url('/competitions/competitions/edit/');
		$this->form_redirect = url('/competitions/competitions');
		
		$this->list_custom_body_cells = PATH_APP.'/modules/competitions/controllers/competitions_competitions/_list_body_cb.htm';
		$this->list_custom_head_cells = PATH_APP.'/modules/competitions/controllers/competitions_competitions/_list_head_cb.htm';
	}
	
    public function index()
	{
		$this->app_page_title = 'Competitions';
		$this->app_page = 'competitions';
    }
    
    protected function index_onRefresh()
	{
		$this->renderPartial('competitions_page_content');
	}
	
	protected function index_onDeleteSelected()
	{
		$competitions_processed = 0;
		$competition_ids = post('list_ids', array());
		$this->viewData['list_checked_records'] = $competition_ids;

		foreach ($competition_ids as $competition_id)
		{
			$competition_id = trim($competition_id);
			if (!strlen($competition_id))
				continue;
	
			$competition = null;
			try
			{	
				$competition = Competition_Name::create()->find($competition_id);
				if ($competition)
					$competition->delete();

				$competitions_processed++;
			}
			catch (Exception $ex)
			{
				if (!$competition)
					Phpr::$session->flash['error'] = $ex->getMessage();
				else
					Phpr::$session->flash['error'] = 'Error deleting competitions "'.$competition->id.'": '.$ex->getMessage();

				break;
			}
		}

		if ($competitions_processed)
		{
			if ($competitions_processed > 1)
				Phpr::$session->flash['success'] = $competitions_processed.' competitions have been successfully deleted.';
			else
				Phpr::$session->flash['success'] = '1 competition has been successfully deleted.';
		}

		$this->renderPartial('competitions_page_content');
	}
    
	protected function evalEntriesNum()
	{
		return Competitions_Competition::create()->requestRowCount();
	}
	
}