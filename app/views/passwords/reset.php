<?php require APP_ROOT . '/views/inc/header.php'; ?>
<div class="row">
	<div class="col-md-6 mx-auto">
		<div class="card card-body bg-light mt-5">
			<?php flash('mail_success'); ?>
			<?php flash('mail_failure'); ?>
			<?php flash('mail_danger'); ?>
<form action=<?= URLROOT.'/passwords/reset'; ?> method="POST">
	<input type="hidden" id="token" name="token" value="<?= $_SESSION['token'] ?>"/>
	<div class="form-group">
		<label for="email">Email: <sup>*</sup></label>
		<input id="email" type="email" name="email" class="form-control form-control-lg <?=
		(!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?= $data['email'] ?>"
		placeholder="Enter email here">
		<span class="invalid-feedback"><?= $data['email_err']; ?></span>
	</div>
	<!-- Button trigger modal -->
	<div class="row">
		<div class="col">
			<input class="btn btn-success btn-block" type="submit" name="pass_recovery" value="Submit">
		</div>
	</div>
	<a href="<?= URLROOT ?>/users/login" class="btn btn-link btn-sm mb-2">Go back</a>
</form>
</div>
</div>
</div>
<?php require APP_ROOT . '/views/inc/footer.php'; ?>
