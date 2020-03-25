<div class="card card-body mb-5">
	<form class="blog-filter mb-0">
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label>Keyword</label>
					<input type="text" class="form-control" name="keyword" maxlength="50" value="{{ isset($request['keyword']) ? $request['keyword'] : null }}">
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>Category</label>
					<select name="category" class="form-control">
						<option value="">- All Category -</option>
						<?php
						$selc = isset($request['category']) ? $request['category'] : null;
						?>
						@foreach(SiteInstance::post()->categories() as $cat)
						<option {{ $selc == $cat->id ? 'selected' : '' }} value="{{ $cat->id }}">{{ $cat->outputTranslate('name') }}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group" style="margin-top:2.25em;">
					<button class="btn btn-block btn-primary"><i class="fa fa-fw fa-filter"></i> Filter</button>
				</div>
			</div>
		</div>
	</form>
</div>
