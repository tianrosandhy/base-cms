<div class="card card-body mt-4">
	<h4 class="mb-3">Comment Section</h4>
	<?php
	$baseComment = $data->comment->where('reply_to', null);
	?>
	@if($baseComment->count() > 0)
		@foreach($baseComment as $comm)
			@include ('post::partials.comment-item', ['row' => $comm])
		@endforeach
	@else
	<div class="alert alert-warning">No comment in this post</div>
	@endif


	<form action="{{ route('admin.post.comment', ['id' => $data->id]) }}" method="post" class="post-comment mt-4">
		{{ csrf_field() }}
		<input type="hidden" name="is_admin_reply" value="">
		<input type="hidden" name="reply_to" value="">
		<textarea name="message" class="form-control" placeholder="Admin Comment"></textarea>
		<button class="btn btn-primary" type="submit">Post Comment</button>
	</form>
</div>

<script>
$(function(){
	$(document).on('click', '.btn-reply', function(e){
		e.preventDefault();
		$(this).slideUp();
		$(this).next('.form-reply').slideDown();
	});
});
</script>