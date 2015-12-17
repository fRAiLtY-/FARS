<?php

class Competitions_Actions extends Cms_ActionScope
{
	public function on_FilterLocation()
	{
		$state_id = post('state');
		$events = Competitions_Competition::create()->where('state_id=?', $state_id)->find_all();

		$this->data['members'] = $events;
	}
}