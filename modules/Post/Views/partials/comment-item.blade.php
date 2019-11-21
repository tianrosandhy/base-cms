<div class="card card-body mt-2" style="{!! $row->is_active == 1 ? 'opacity:1' : 'opacity:.25' !!}">
	<div>
		<strong>{{ $row->name }}</strong> {!! $row->is_admin_reply ? '<span class="badge badge-primary">Admin</span>' : '' !!}
		<br>
		{{ $row->email }} - {{ $row->phone }}
	</div>
	<div style="font-size:smaller;">{{ date('d M Y H:i:s', strtotime($row->created_at)) }}</div>
	<div class="mt-3">
		<p class="lead">{{ $row->message }}</p>
	</div>

	@if(!isset($as_child))
		<a href="#" class="btn btn-sm btn-secondary btn-reply">Reply</a>
		<div class="form-reply" style="display:none;">
			<form action="{{ route('admin.post.detail', ['id' => $data->id]) }}" method="post" class="post-rowent">
				{{ csrf_field() }}
				<input type="hidden" name="is_admin_reply" value="1">
				<input type="hidden" name="reply_to" value="{{ $row->id }}">
				<textarea name="message" class="form-control" placeholder="Admin Reply rowent	"></textarea>
				<button class="btn btn-primary" type="submit">Reply</button>
			</form>
		</div>
		@foreach($row->child as $child)
		<div class="post-reply">
			@include ('post::partials.comment-item', [
				'row' => $child,
				'as_child' => true
			])
		</div>
		@endforeach
	@endif
</div>