<?php require APP_ROOT . '/views/inc/header.php'; ?>
<div class="row">
	<div class="col-md-6 mx-auto">
		<div class="card card-body bg-light mt-5">
			<?php flash('register_danger'); ?>
			<h2>Create Account</h2>
			<form action=<?= URLROOT.'/users/register'; ?> method="POST">
				<input type="hidden" id="token" name="token" value="<?= $_SESSION['token'] ?>"/>
				<div class="form-group">
					<label for="name">Name: <sup>*</sup></label>
					<input id="name" type="text" name="name" class="form-control form-control-lg <?=
					(!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?= $data['name'] ?>"
					placeholder="Enter name here">
					<span class="invalid-feedback"><?= $data['name_err']; ?></span>
				</div>
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
					(!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value=""
					placeholder="Enter password here">
					<span class="invalid-feedback"><?= $data['password_err']; ?></span>
				</div>
				<div class="form-group">
					<label for="confirm_password">Password Confirm: <sup>*</sup></label>
					<input id="confirm_password" type="password" name="confirm_password" class="form-control form-control-lg <?=
					(!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" value=""
					placeholder="Confirm Password here">
					<span class="invalid-feedback"><?= $data['confirm_password_err']; ?></span>
				</div>
				<div class="row">
					<div class="col">
						<input class="btn btn-success btn-block" type="submit" name="register" value="Register">
					</div>
					<div class="col">
						<a href="<?= URLROOT ?>/users/login" class="btn btn-light btn-block">Login</a>
					</div>
				</div>
				<div class="row">
					<div class="col mt-3">
						<p>Fields with * are required</p>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<?php require APP_ROOT . '/views/inc/footer.php'; ?>
