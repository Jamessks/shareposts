<?php require APP_ROOT . '/views/inc/header.php'; ?>
<div class="row">
	<div class="col-md-6">
		<h1>Posts</h1>
		<?php flash('post_success'); ?>
		<?php flash('post_failure'); ?>
		<?php flash('post_edit_warning'); ?>
		<?php flash('post_delete_success'); ?>
		<?php flash('post_delete_warning'); ?>
		<?php flash('post_delete_failure'); ?>
	</div>
	<?php if(isLoggedIn()) : ?>
	<div class="col-md-6">
		<a href="<?php echo URLROOT; ?>/posts/add" class="btn btn-primary pull-right">
			<i class="fa fa-pencil"></i> Add Post
		</a>
	</div>
<?php endif ?>
</div>
<?php foreach($data['posts'] as $post) : ?>
	<div class="card card-body mb-3">
		<h4 class="card-title"><?= $post->title; ?></h4>
		<div class="bg-light p-2 mb-3">
		Written by <?= $post->name; ?> on <?= $post->created_at ?>
		</div>
	<p class="card-text"><?= $post->body; ?></p>
	<a href="<?= URLROOT; ?>/posts/show/<?= $post->post_id ?>" class="btn btn-dark">More</a>
</div>
<?php endforeach; ?>
<?php require APP_ROOT . '/views/inc/footer.php'; ?>
