<?php

	class FlynsarmyRedirects_RedirectCsvImportModel extends Backend_CsvImportModel
	{
		public $table_name = 'flynsarmyredirects_redirects';

		//public $implement = 'Db_Sortable';

		public $has_many = array(
			'csv_file'=>array('class_name'=>'Db_File', 'foreign_key'=>'master_object_id', 'conditions'=>"master_object_class='FlynsarmyRedirects_RedirectCsvImportModel'", 'order'=>'id', 'delete'=>true),
			'config_import'=>array('class_name'=>'Db_File', 'foreign_key'=>'master_object_id', 'conditions'=>"master_object_class='FlynsarmyRedirects_RedirectCsvImportModel'", 'order'=>'id', 'delete'=>true)
		);

		public $belongs_to = array(
			'group'=>array('class_name'=>'FlynsarmyRedirects_Group', 'foreign_key'=>'group_id')
		);

		public function define_columns($context = null)
		{
			parent::define_columns($context);

			//$this->define_column('check_for_duplicates', 'Check for Duplicates');
			$this->define_relation_column('group', 'group', 'Redirect Group ', db_varchar, '@name');
		}

		public function define_form_fields($context = null)
		{
			parent::define_form_fields($context);

			//$this->add_form_field('check_for_duplicates')->comment("If you check this checkbox, LemonStand will not import redirects with a 'From URL' and 'To URL' that match ones already in this group.", 'above', true);
			$this->add_form_field('group')->comment('Please select a redirect group for imported redirects', 'above')->cssClassName('expandable')->emptyOption('<please select>');
		}

		public function import_csv_data($data_model, $session_key, $column_map, $import_manager, $delimeter, $first_row_titles)
		{
			@set_time_limit(3600);

			/*
			 * Validate import configuration
			 */
			if (!$import_manager->csvImportDbColumnPresented($column_map, 'from_url'))
				throw new Phpr_ApplicationException('Please specify a matching column for the \'From URL\' field.');
			if (!$import_manager->csvImportDbColumnPresented($column_map, 'to_url'))
				throw new Phpr_ApplicationException('Please specify a matching column for the \'To URL\' field.');
			if (!$this->group)
				throw new Phpr_ApplicationException('Please select a redirect group.');

			$added = 0;
			$skipped = 0;
			$skipped_rows = array();
			$updated = 0;
			$errors = array();

			$csv_handle = $import_manager->csvImportGetCsvFileHandle();
			$column_definitions = $data_model->get_csv_import_columns();

			$first_row_found = false;
			$line_number = 0;
			while (($row = fgetcsv($csv_handle, 2000000, $delimeter)) !== FALSE)
			{
				$line_number++;

				if (Phpr_Files::csvRowIsEmpty($row))
					continue;

				if (!$first_row_found)
				{
					$first_row_found = true;

					if ($first_row_titles)
						continue;
				}

				try
				{
					$bind = array();

					if (!$this->auto_create_groups)
						$bind['group_id'] = $this->group->id;

					foreach ($column_map as $column_index=>$db_names)
					{
						if (!array_key_exists($column_index, $row))
							continue;

						$column_value = trim($row[$column_index]);

						foreach ($db_names as $db_name)
						{
							/*
							 * Skip unknown columns
							 */

							if (!array_key_exists($db_name, $column_definitions))
								continue;

							if ($column_definitions[$db_name]->type == db_bool)
								$bind[$db_name] = Core_CsvHelper::boolean($column_value) ? '1' : '0';
							else
								$bind[$db_name] = $column_value;
						}
					}

					if ( !isset($bind['is_enabled']) )
						$bind['is_enabled'] = 1;

					$this->validate_fields($bind);

					/*
					 * Create or update a customer record
					 */

					$redirect = new FlynsarmyRedirects_Redirect($bind);
					$redirect->save();
					$added++;

				} catch (Exception $ex)
				{
					$errors[$line_number] = $ex->getMessage();
				}
			}

			$result = array(
				'added'=>$added,
				'skipped'=>$skipped,
				'skipped_rows'=>$skipped_rows,
				'updated'=>$updated,
				'errors'=>$errors,
				'warnings'=>array()
			);

			return (object)$result;
		}

		protected function validate_fields(&$bind, $existing_customer = false)
		{
			if (!array_key_exists('from_url', $bind) || !strlen($bind['from_url']))
				throw new Phpr_ApplicationException('Redirect From URL is not specified');

			if (!array_key_exists('to_url', $bind) || !strlen($bind['to_url']))
				throw new Phpr_ApplicationException('Redirect To URL is not specified');

			if (!array_key_exists('match_type', $bind) || !strlen($bind['match_type']))
				$bind['match_type'] = 'Starts with';
			else
			{
				$match_type_options = array_flip(FlynsarmyRedirects_Redirect::create()->get_match_type_options());
				if ( isset($match_type_options[$bind['match_type']]) )
					$bind['match_type'] = $match_type_options[$bind['match_type']];
				else
					throw new Phpr_ApplicationException('Match type must be one of: ' . implode(', ', array_keys($match_type_options)));
			}

			if (!array_key_exists('redirect_code', $bind) || !strlen($bind['redirect_code']))
				$bind['redirect_code'] = '301';
			else
			{
				$redirect_code_options = array_flip(FlynsarmyRedirects_Redirect::create()->get_redirect_code_options());
				if ( isset($redirect_code_options[$bind['redirect_code']]) )
					$bind['redirect_code'] = $redirect_code_options[$bind['redirect_code']];
				else
					throw new Phpr_ApplicationException('Redirect code must be one of: ' . implode(', ', array_keys($redirect_code_options)));
			}

			if (!array_key_exists('hits', $bind) || !strlen($bind['hits']))
				$bind['hits'] = 0;
		}
	}

?>
