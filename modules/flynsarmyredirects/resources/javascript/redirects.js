function groups_selected()
{
	return $('group_list_table')
		.getElements('tbody tr td.checkbox input')
		.some(function(element) {
			return element.checked;
		});
}

function delete_selected_groups()
{
	if (!groups_selected())
	{
		alert('Please select groups to delete.');
		return false;
	}

	return $('group_list_table').getForm().sendPhpr(
		'index_onDeleteSelected',
		{
			confirm: 'Do you really want to delete selected group(s)?',
			loadIndicator: {show: false},
			onBeforePost: LightLoadingIndicator.show.pass('Loading...'),
			onComplete: LightLoadingIndicator.hide,
			onAfterUpdate: update_scrollable_toolbars,
			update: 'templates_page_content'
		}
	);
}

function redirects_selected()
{
	return $('redirect_list_table')
		.getElements('tbody tr td.checkbox input')
		.some(function(element) {
			return element.checked;
		});
}

function delete_selected_redirects(session_key)
{
	if (!redirects_selected())
	{
		alert('Please select redirects to delete.');
		return false;
	}

	return $('redirect_list_table').getForm().sendPhpr(
		'onDeleteSelectedRedirects',
		{
			confirm: 'Do you really want to delete selected redirect(s)?',
			loadIndicator: {show: false},
			onBeforePost: LightLoadingIndicator.show.pass('Loading...'),
			onComplete: LightLoadingIndicator.hide,
			onAfterUpdate: function() {
				update_scrollable_toolbars();
				make_redirects_sortable(session_key);
			},
			update: 'redirect_list'+session_key
		}
	);
}

function set_redirects_enabled(session_key, enabled_state)
{
	if (!redirects_selected())
	{
		alert('Please select redirects to '+(enabled_state?'enable':'disable')+'.');
		return false;
	}

	return $('redirect_list_table').getForm().sendPhpr(
		'edit_onSetRedirectEnableState',
		{
			confirm: 'Do you really want to '+(enabled_state?'enable':'disable')+' selected redirect(s)?',
			loadIndicator: {show: false},
			extraFields: {'enabled': enabled_state},
			onBeforePost: LightLoadingIndicator.show.pass('Loading...'),
			onComplete: LightLoadingIndicator.hide,
			onAfterUpdate: function() {
				update_scrollable_toolbars();
				make_redirects_sortable(session_key);
			},
			update: 'redirect_list'+session_key
		}
	);
}