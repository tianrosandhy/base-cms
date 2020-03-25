<div class="card card-body">
	<form class="blog-filter">
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label>Keyword</label>
					<input type="text" class="form-control" name="keyword" maxlength="50">
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>Category</label>
					<select name="category" class="form-control">
						<option value="">- All Category -</option>
						@foreach(SiteInstance::post()->categories() as $cat)
						<option value="{{ $cat->id }}">{{ $cat->outputTranslate('name') }}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group" style="margin-top:1.5em;">
					<button class="btn btn-block btn-primary"><i class="fa fa-fw fa-filter"></i> Filter</button>
				</div>
			</div>
		</div>
	</form>
</div>
