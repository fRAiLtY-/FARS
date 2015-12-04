<?php $this->render_partial('layout:header'); ?>
	<nav role="navigation" class="navbar navbar-default">
        <div class="navbar-header">
            <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div id="navbarCollapse" class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-center">
                <li class="active"><a href="/members">Dashboard</a></li>
                <li><a href="/members/messages">Messages</a></li>
                <li><a href="/members/events">Events</a></li>
                <li><a href="/members/subscription">Subscription</a></li>
                <li><a href="/members/logout">Logout</a></li>
            </ul>
        </div>
    </nav>
	<section class="members">
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<?php $this->render_page(); ?>	
				</div>
			</div>
		</div>
	</section>
<?php $this->render_partial('layout:footer'); ?>