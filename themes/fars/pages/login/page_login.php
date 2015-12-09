<section class="login page">
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-xs-12">
				<div class="panel">
					<div class="panel-heading">Login</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-xs-12">
								<?php echo open_form() ?>
									<?php echo Fars::show_error() ?>
									<div class="form-group">
										<input class="form-control" id="login_email" type="text" name="email" placeholder="Email address" value="<?= h(post('email')) ?>"/>
									</div>
									
									<div class="form-group">
										<input class="form-control" id="login_password" type="password" name="password" placeholder="Password"/>
									</div>
									
									<div class="form-group">
										<button class="btn btn-lg btn-default btn-block" type="submit" name="login" value="login"><i class="fa fa-check"></i></button>
										<input type="hidden" name="redirect" value="/members"/>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-xs-12">
				<div class="panel">
					<div class="panel-heading">Registration</div>
					<div class="panel-body">
						<div class="row">
							<?= open_form() ?>
								<div class="col-xs-6">
									<div class="form-group">
										<input class="form-control" name="first_name" value="<?= h(post('first_name')) ?>" id="first_name" type="text" placeholder="First name" />
									</div>
								</div>
								
								<div class="col-xs-6">
									<div class="form-group">
										<input class="form-control" name="last_name" value="<?= h(post('last_name')) ?>" id="last_name" type="text" placeholder="Last name" />
									</div>
								</div>
								
								<div class="col-xs-6">
									<div class="form-group">
										<input class="form-control" id="password" type="password" name="password" value="" autocomplete="off" placeholder="Password"/>
									</div>
								</div>
								
								<div class="col-xs-6">
									<div class="form-group">
										<input class="form-control" id="password_confirm" type="password" name="password_confirm" value="" autocomplete="off" placeholder="Password (again)"/>
									</div>
								</div>
								
								<div class="col-xs-6">
									<div class="form-group">
										<input class="form-control" id="email" type="text" name="email" value="<?= h(post('email')) ?>" placeholder="Email address"/>
									</div>
								</div>
								
								<div class="col-xs-6">
									<div class="form-group">
										<input class="form-control" id="fars_number" type="text" name="fars_number" value="<?= h(post('fars_number')) ?>" placeholder="FARS number"/>
									</div>
								</div>
								
								<div class="col-xs-12">
									<div class="form-group">
										<button class="btn btn-block btn-default btn-lg" type="submit" name="signup" value="signup"><i class="fa fa-check"></i></button>
										<input type="hidden" name="redirect" value="/"/>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>