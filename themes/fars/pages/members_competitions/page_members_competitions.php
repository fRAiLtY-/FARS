<div class="row">
	<div class="col-xs-12">
		<div class="panel">
			<div class="panel-heading">Competition Entry</div>
			<div class="panel-body">
				<table class="table table-flat table-hover">
					<thead>
						<tr>
							<th>Competition</th>
							<th>Date</th>
							<th>Location</th>
						</tr>
					</thead>
					<tbody>
						<?php $competitions = Competitions_Competition::create()->find_all(); ?>
						<?php foreach($competitions as $competition) : ?>
						<tr>
							<td><a href="<?php echo site_url('/members/competitions/view/'.$competition->id); ?>"><?php echo $competition->name; ?></a></td>
							<td><a href="<?php echo site_url('/members/competitions/view/'.$competition->id); ?>"><?php echo $competition->date->format('%e %b %Y'); ?></a></td>
							<td><a href="<?php echo site_url('/members/competitions/view/'.$competition->id); ?>"><?php echo Shop_CountryState::create()->find_by_id($competition->location_id)->name; ?></a></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>