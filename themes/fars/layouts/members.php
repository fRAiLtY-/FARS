<?php $this->render_partial('layout:header'); ?>
	<section class="members page">
		<div class="container">
			<div class="row">
				<div class="col-xs-3">
					<div class="panel panel-profile">
						<div class="panel-heading">
							<?php $grade = Test_Grade::create()->find_by_id($this->customer->x_grade); ?>
							<img src="<?php echo Gravitar::url($this->customer->email, 200); ?>" alt="<?php echo $this->customer->first_name; ?>" class="img-circle" width="150" height="150">
							<h4>(#<?php echo $this->customer->x_fars_number; ?>)&nbsp;<?php echo $this->customer->first_name; ?>&nbsp;<?php echo $this->customer->last_name; ?></h4>
							<p class="subtext"><i class="fa fa-trophy"></i>&nbsp;<?php echo $grade->grade ?></p>
						</div>
						<div class="list-group">
							<a class="list-group-item" href="#">Dashboard</a>
							<a class="list-group-item" href="#">Gradings</a>
							<a class="list-group-item" href="#">Messages</a>
							<a class="list-group-item" href="#">Subscription</a>
							<a class="list-group-item" href="#">Competition</a>
							<a class="list-group-item" href="<?php echo site_url('members/logout'); ?>">Logout</a>
						</div>
					</div>
				</div>
				<div class="col-xs-9">
					<?php $this->render_page(); ?>	
				</div>
			</div>
		</div>
	</section>
<?php $this->render_partial('layout:footer'); ?>