<!DOCTYPE html>
<html>
<head>
	<?php $this->render_partial('layout:head'); ?>
</head>
<body data-page="<?php echo $this->page->directory_name ?>">
	
	<div class="container container-middle">
		
		<section class="top">
			<div class="bar">
				<div class="row">
					<div class="col-xs-12">
						<ul class="bar-links">
							<li><a href="#"><i class="fa fa-flickr"></i></a></li>
							<li><a href="#"><i class="fa fa-youtube"></i></a></li>
							<li><a href="#"><i class="fa fa-facebook"></i></a></li>
							<li><a href="#"><i class="fa fa-twitter"></i></a></li>
							<?php if($this->customer): ?>
								<li><a href="/members/logout">Logout</a></li>
							<?php else: ?>
								<li><a href="/members/login">Login</a></li>
								<li><a href="/members/register">Register</a></li>
							<?php endif; ?>
						</ul>
					</div>
				</div>
			</div>
			<nav>
				<div class="row">
					<div class="col-md-4 col-xs-11 logo">
						<a href="/"><img src="<?php echo theme_resource_url('images/fars_logo.png'); ?>" height="70" alt="Fedaration of Artistic Roller Skating" /></a>
					</div>
					<div class="col-md-6 col-xs-1">
						<ul class="nav">
							<li><a href="#">Home</a></li>
							<li><a href="#">Events</a></li>
							<li><a href="#">Coaching</a></li>
							<li><a href="#">Results</a></li>
							<li><a href="#">Find a Club</a></li>
							<li><a href="#">Contact Us</a></li>
						</ul>
					</div>
					<div class="col-md-2 xs-hidden search">
					    <div class="input-group pull-left">
					      <input type="text" class="form-control" placeholder="Search">
					      <span class="input-group-btn">
					        <button class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
					      </span>
						</div>
					</div> 
				</div>
			</nav>
		</section>