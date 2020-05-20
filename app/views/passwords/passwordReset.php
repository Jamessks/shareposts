<?php require APP_ROOT . '/views/inc/header.php'; ?>
<div class="row">
	<div class="col-md-6 mx-auto">
		<div class="card card-body bg-light mt-5">
			<h2>Reset Password</h2>
<form action=<?= URLROOT.'/passwords/passwordReset' ?> method="POST">
	<input type="hidden" id="pftoken" name="pftoken" value="<?= $data['pftoken']; ?>"/>
	<input type="hidden" id="token" name="token" value="<?= $_SESSION['token'] ?>"/>
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
	<!-- Button trigger modal -->
	<div class="row">
		<div class="col">
			<input class="btn btn-success btn-block" type="submit" name="register" value="Submit">
		</div>
	</div>
	<a href="<?= URLROOT ?>/users/login" class="btn btn-link btn-sm mb-2">Back to login</a>
</form>
</div>
</div>
</div>
<?php require APP_ROOT . '/views/inc/footer.php'; ?>
