<?php require APP_ROOT . '/views/inc/header.php'; ?>
<div class="row">
	<div class="col-md-6 mx-auto">
		<div class="card card-body bg-light mt-5">
			<?php flash('register_success'); ?>
			<?php flash('register_danger'); ?>
			<h2>Login</h2>
			<form action=<?= URLROOT.'/users/login'; ?> method="POST">
				<input type="hidden" id="token" name="token" value="<?= $_SESSION['token'] ?>"/>
				<div class="form-group">
					<label for="email">Email: <sup>*</sup></label>
					<input id="email" type="email" name="email" class="form-control form-control-lg <?=
					(!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?= $data['email'] ?>"
					placeholder="Enter email here">
					<span class="invalid-feedback"><?= $data['email_err']; ?></span>
				</div>
				<div class="form-group">
					<label for="password">Password: <sup>*</sup></label>
					<input id="password" type="password" name="password" class="form-control form-control-lg <?=
					(!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?= $data['password'] ?>"
					placeholder="Enter password here">
					<span class="invalid-feedback"><?= $data['password_err']; ?></span>
				</div>
				<!-- Button trigger modal -->
				<a href="<?= URLROOT ?>/passwords/reset" class="btn btn-link btn-sm mb-2">Forgot password</a>
				<div class="row">
					<div class="col">
						<input class="btn btn-success btn-block" type="submit" name="register" value="Login">
					</div>
					<div class="col">
						<a href="<?= URLROOT ?>/users/register" class="btn btn-light btn-block">No account? Register</a>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<?php require APP_ROOT . '/views/inc/footer.php'; ?>
