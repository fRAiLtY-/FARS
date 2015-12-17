function refresh_list()
{
	$('listCompetitions_Competitions_index_list_body').getForm().sendPhpr(
		'index_onRefresh',
		{
			loadIndicator: { show: false },
			onBeforePost: LightLoadingIndicator.show.pass('Loading...'), 
			onComplete: LightLoadingIndicator.hide,
			update: 'competitions_page_content',
			onAfterUpdate: update_scrollable_toolbars
		}
	);
	
	$('listCompetitions_Categories_index_list_body').getForm().sendPhpr(
		'index_onRefresh',
		{
			loadIndicator: { show: false },
			onBeforePost: LightLoadingIndicator.show.pass('Loading...'), 
			onComplete: LightLoadingIndicator.hide,
			update: 'categories_page_content',
			onAfterUpdate: update_scrollable_toolbars
		}
	);

	return false;
}

function delete_selected()
{
	if (!objects_selected())
	{
		alert('Please select entries to delete.');
		return false;
	}
	
	$('listCompetitions_Competitions_index_list_body').getForm().sendPhpr(
		'index_onDeleteSelected',
		{
			loadIndicator: {show: false},
			onBeforePost: LightLoadingIndicator.show.pass('Deleting...'),
			onComplete: LightLoadingIndicator.hide,
			update: 'competitions_page_content',
		}
	);

	return false;
}

function objects_selected() {
	return $('listCompetitions_Competitions_index_list_body').getElements('tr td.checkbox input').some(function(element){return element.checked});
}