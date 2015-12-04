<?php

class Competition_Actions extends Cms_ActionScope
{
	public function on_FilterLocation()
	{
		$state_id = post('state');
		$events = Competition_Name::create()->where('state_id=?', $state_id)->find_all();

		$this->data['members'] = $events;
	}
}