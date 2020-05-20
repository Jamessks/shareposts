<?php require APP_ROOT . '/views/inc/header.php'; ?>
<div class="row">
	<div class="col-md-12 mx-auto">
		<a href="<?= URLROOT.'/posts/index'; ?>" class="btn btn-light"><i class="fa fa-backward"></i> Back</a>
		<div class="card card-body bg-light mt-5">
			<h2>New Post</h2>
			<form action=<?= URLROOT.'/posts/add'; ?> method="POST">
				<input type="hidden" id="token" name="token" value="<?= $_SESSION['token'] ?>"/>
				<div class="form-group">
					<label for="post-title">Post Title: <sup>*</sup></label>
					<input id="post-title" type="text" name="post_title" class="form-control form-control-lg <?=
					(!empty($data['post_title_err'])) ? 'is-invalid' : ''; ?>" value="<?= $data['post_title'] ?>"
					placeholder="Enter Post Title here">
					<span class="invalid-feedback"><?= $data['post_title_err']; ?></span>
				</div>
				<div class="form-group">
					<label for="post-body">Post Body: <sup>*</sup></label>
					<textarea id="post-body" type="text" name="post_body" class="form-control form-control-lg <?=
					(!empty($data['post_body_err'])) ? 'is-invalid' : ''; ?>" value="<?= $data['post_body'] ?>"
					placeholder="Enter Post Body here"><?= $data['post_body']; ?></textarea>
					<span class="invalid-feedback"><?= $data['post_body_err']; ?></span>
				</div>
				<div class="row">
					<div class="col">
						<input class="btn btn-success btn-block" type="submit" value="Submit Post">
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<?php require APP_ROOT . '/views/inc/footer.php'; ?>
