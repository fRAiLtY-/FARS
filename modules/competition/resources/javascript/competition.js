function refresh_list()
{
	$('listCompetition_Manager_index_list_body').getForm().sendPhpr(
		'index_onRefresh',
		{
			loadIndicator: { show: false },
			onBeforePost: LightLoadingIndicator.show.pass('Loading...'), 
			onComplete: LightLoadingIndicator.hide,
			update: 'competition_page_content',
			onAfterUpdate: update_scrollable_toolbars
		}
	);

	return false;
}

function delete_selected()
{
	if (!clients_selected())
	{
		alert('Please select entries to delete.');
		return false;
	}
	
	$('listCompetition_Manager_index_list_body').getForm().sendPhpr(
		'index_onDeleteSelected',
		{
			loadIndicator: {show: false},
			onBeforePost: LightLoadingIndicator.show.pass('Deleting...'),
			onComplete: LightLoadingIndicator.hide,
			update: 'competition_page_content',
		}
	);

	return false;
}

function clients_selected() {
	return $('listCompetition_Manager_index_list_body').getElements('tr td.checkbox input').some(function(element){return element.checked});
}