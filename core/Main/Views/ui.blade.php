@extends ('main::master')

@include ('main::assets.dropzone')
@include ('main::assets.fancybox')
@include ('main::assets.scroll-overflow')

@section ('content')
<div class="card">
	<div class="card-header">Typography</div>
	<div class="card-body">
		<div class="row">
			<div class="col-sm-5">
				<h1>Heading 1</h1>
				<h2>Heading 2</h2>
				<h3>Heading 3</h3>
				<h4>Heading 4</h4>
				<h5>Heading 5</h5>
				<h6>Heading 6</h6>
			</div>
			<div class="col-sm-7">
				<h1 class="display-1">Display 1</h1>				
				<h2 class="display-2">Display 2</h1>				
				<h3 class="display-3">Display 3</h1>				
				<h4 class="display-4">Display 4</h1>				
			</div>
		</div>
	</div>
</div>

<div class="card mt-5">
	<div class="card-header">Alerts</div>
	<div class="card-body">
		<div class="alert alert-info">
			<i class="fa fa-fw fa-info"></i> <strong>Info : </strong> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Tenetur doloremque rerum eaque eveniet recusandae quibusdam?
		</div>
		
		<div class="alert alert-warning">
			<i class="fa fa-fw fa-exclamation-triangle"></i> <strong>Warning : </strong> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Tenetur doloremque rerum eaque eveniet recusandae quibusdam?
		</div>

		<div class="alert alert-success">
			<i class="fa fa-fw fa-check-circle"></i> <strong>Success : </strong> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Tenetur doloremque rerum eaque eveniet recusandae quibusdam?
		</div>
		
		<div class="alert alert-danger">
			<i class="fa fa-fw fa-times-circle"></i> <strong>Error : </strong> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Tenetur doloremque rerum eaque eveniet recusandae quibusdam?
		</div>
		
	</div>
</div>

<div class="card mt-5">
	<div class="card-header">Control Label & Buttons</div>
	<div class="card-body">
		<?php
		$modes = ['primary', 'secondary', 'info', 'warning', 'success', 'danger'];
		?>
		<div class="padd">
			@foreach($modes as $mode)
			<span class="badge badge-{{ $mode }}">Badge {{ $mode }}</span>
			@endforeach
		</div>
		
		<div class="padd">
			@foreach($modes as $mode)
			<span class="btn btn-{{ $mode }}">Button {{ $mode }}</span>
			@endforeach
		</div>

		<div class="padd">
			<span class="btn btn-primary btn-sm">Small Button</span>
			<span class="btn btn-primary">Normal Button</span>
			<span class="btn btn-primary btn-lg">Large Button</span>
		</div>

		<div class="padd">
			<span class="btn btn-primary">
				<i class="fa fa-send fa-fw"></i>
				Button with Icon
			</span>
		</div>

		<div class="padd">
			<div class="btn-group">
				<span class="btn btn-primary">Button Group</span>
				<span class="btn btn-secondary">Button Group</span>
				<span class="btn btn-danger">Button Group</span>
			</div>
		</div>
		
	</div>
</div>

<div class="row mt-5">
	<div class="col-sm-6">
		<div class="card">
			<div class="card-header">
				Form Style 1
			</div>
			<div class="card-body">
				<div class="form-group custom-form-group">
					<label>Normal Text Input</label>
					<input type="text" class="form-control" placeholder="Text Placeholder">
				</div>

				<div class="form-group custom-form-group">
					<label>Normal Number Input</label>
					<input type="number" class="form-control" placeholder="Ex: 123">
				</div>

				<div class="form-group custom-form-group">
					<label>Dropdown Input</label>
					<select class="form-control select2">
						<option value="">- Select Value -</option>
						<option value="">Option 1</option>
						<option value="">Option 2</option>
						<option value="">Option 3</option>
						<option value="">Option 4</option>
					</select>
				</div>

				<div class="form-group custom-form-group">
					<label>Multiple Selection</label>
					<select multiple class="form-control select2">
						<option>Apple</option>
						<option>Papaya</option>
						<option selected>Tomato</option>
						<option>Cat</option>
						<option>Taxi</option>
						<option selected>Handphone</option>
						<option>Watermelon</option>
						<option>Headset</option>
						<option>Hand</option>
						<option>Foot</option>
					</select>
				</div>

				<div class="form-group custom-form-group">
					<label>Datepicker</label>
					<input type="text" class="form-control datepicker" data-mask="0000-00-00" placeholder="YYYY-mm-dd">
				</div>
				
				<div class="form-group custom-form-group">
					<label>Datepicker Range</label>
					@include ('main::inc.daterange-helper', [
						'attr' => 'class="form-control"'
					])
				</div>

				<div class="form-group custom-form-group">
					<label>Textarea</label>
					<textarea data-textarea maxlength=300 class="form-control"></textarea>
					<span class="feedback"></span>
				</div>

				<div class="form-group">
					<label class="checkbox">
						<input type="checkbox" value="1" id="checkbox1">
						<span>Checkbox Label</label>
					</label>

					<label class="checkbox">
						<input type="checkbox" value="1" id="checkbox1">
						<span>Checkbox Label</label>
					</label>

					<label class="checkbox">
						<input type="checkbox" value="1" id="checkbox1">
						<span>Checkbox Label</label>
					</label>
				</div>


				<div class="form-group">
					<label class="checkbox">
						<input type="radio" name="test" value="1" id="checkbox1">
						<span>Radio Label</label>
					</label>

					<label class="checkbox">
						<input type="radio" name="test" value="1" id="checkbox1">
						<span>Radio Label</label>
					</label>

					<label class="checkbox">
						<input type="radio" name="test" value="1" id="checkbox1">
						<span>Radio Label</label>
					</label>
				</div>

				<div class="form-group custom-form-group">
					<label>Image Upload</label>
					@include ('main::inc.dropzone')
				</div>

			</div>
		</div>
		
	</div>
	<div class="col-sm-6">
		<div class="card">
			<div class="card-header">
				Form Style 2
			</div>
			<div class="card-body">
				<div class="form-group">
					<label>Normal Text Input</label>
					<input type="text" class="form-control" placeholder="Text Placeholder">
				</div>

				<div class="form-group">
					<label>Normal Number Input</label>
					<input type="number" class="form-control" placeholder="Ex: 123">
				</div>

				<div class="form-group">
					<label>Dropdown Input</label>
					<select class="form-control select2">
						<option value="">- Select Value -</option>
						<option value="">Option 1</option>
						<option value="">Option 2</option>
						<option value="">Option 3</option>
						<option value="">Option 4</option>
					</select>
				</div>

				<div class="form-group">
					<label>Multiple Selection</label>
					<select multiple class="form-control select2">
						<option>Apple</option>
						<option>Papaya</option>
						<option selected>Tomato</option>
						<option>Cat</option>
						<option>Taxi</option>
						<option selected>Handphone</option>
						<option>Watermelon</option>
						<option>Headset</option>
						<option>Hand</option>
						<option>Foot</option>
					</select>
				</div>

				<div class="form-group">
					<label>Datepicker</label>
					<input type="text" class="form-control datepicker" data-mask="0000-00-00" placeholder="YYYY-mm-dd">
				</div>
				
				<div class="form-group">
					<label>Datepicker Range</label>
					@include ('main::inc.daterange-helper', [
						'attr' => 'class="form-control"'
					])
				</div>

				<div class="form-group">
					<label>Textarea</label>
					<textarea data-textarea maxlength=300 class="form-control"></textarea>
					<span class="feedback"></span>
				</div>

				<div class="form-group">
					<label class="checkbox">
						<input type="checkbox" value="1" id="checkbox1">
						<span>Checkbox Label</label>
					</label>

					<label class="checkbox">
						<input type="checkbox" value="1" id="checkbox1">
						<span>Checkbox Label</label>
					</label>

					<label class="checkbox">
						<input type="checkbox" value="1" id="checkbox1">
						<span>Checkbox Label</label>
					</label>
				</div>


				<div class="form-group">
					<label class="checkbox">
						<input type="radio" name="test" value="1" id="checkbox1">
						<span>Radio Label</label>
					</label>

					<label class="checkbox">
						<input type="radio" name="test" value="1" id="checkbox1">
						<span>Radio Label</label>
					</label>

					<label class="checkbox">
						<input type="radio" name="test" value="1" id="checkbox1">
						<span>Radio Label</label>
					</label>
				</div>

				<div class="form-group">
					<label>Image Upload</label>
					@include ('main::inc.dropzone')
				</div>
			</div>
		</div>
		
	</div>
</div>

<div class="card mt-5">
	<div class="card-header">
		Table
	</div>
	<div class="card-body ov">
		<table class="table data-table">
			<thead>
				<tr>
					<th>Field Example</th>
					<th>Image</th>
					<th>Lorem</th>
					<th>Ipsum</th>
					<th>Dolor</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				@for($i=1; $i<30; $i++)
				<tr>
					<td>Data Row 1</td>
					<td><img src="{{ asset('admin_theme/img/broken-image.jpg') }}" style="height:60px;"></td>
					<td>Name Example</td>
					<td>Lorem Ipsum Dolor Sit Amet</td>
					<td>
						<div class="badge badge-success">Active</div>
					</td>
					<td>
						<div class="btn-group-vertical btn-sm">
							<a href="#" class="btn btn-sm btn-secondary">
								<i class="fa fa-fw fa-eye"></i> Detail
							</a>
							<a href="#" class="btn btn-sm btn-info">
								<i class="fa fa-fw fa-pencil"></i> Update
							</a>
							<a href="#" class="btn btn-sm btn-danger">
								<i class="fa fa-fw fa-trash"></i> Delete
							</a>
						</div>
					</td>
				</tr>
				@endfor
			</tbody>
		</table>
	</div>
</div>


<div class="card mt-5">			
	<ul class="nav nav-tabs" id="myTab" role="tablist">
	  <li class="nav-item">
	    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Home</a>
	  </li>
	  <li class="nav-item">
	    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Profile</a>
	  </li>
	  <li class="nav-item">
	    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Contact</a>
	  </li>
	</ul>
	<div class="tab-content" id="myTabContent">
	  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
	  	<div class="card-body">
		  	<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Commodi impedit ad omnis quidem, et ab reprehenderit natus dignissimos. Labore, veniam quod. Quod ratione, molestiae facere tempore dolores voluptatibus nemo quam culpa laborum et architecto nostrum! Tempora reprehenderit ea, magnam. Facere alias modi vero, enim eos quasi commodi ducimus provident reprehenderit.</p>
		  	<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
	  	</div>
	  </div>
	  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
	  	<div class="card-body">
		  	<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Temporibus ipsam excepturi labore natus dolorem, obcaecati ipsum, ea veniam reprehenderit impedit voluptas aliquam assumenda molestias necessitatibus mollitia laboriosam fugit consectetur dolorum at maiores sunt magnam quas! Vel accusamus placeat similique maxime eveniet nesciunt magni neque earum asperiores sapiente architecto, esse libero, laboriosam iusto iste eaque quo quae in. Iste exercitationem ratione, vel aut nemo suscipit, id ipsam tempore, inventore a saepe cumque, sunt odio. Tempore, similique!</p>
		  	<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
	  	</div>
	  </div>
	  <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
	  	<div class="card-body">
		  	<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sunt illo, perferendis ea mollitia labore cum facilis consequuntur deleniti aliquam veniam magni nobis nisi, laudantium beatae placeat quaerat, molestiae animi omnis sapiente! Voluptates perferendis magnam molestias quos et minima quam eligendi aspernatur, aut rerum vero, maxime, unde recusandae incidunt officia a ut cum, facere commodi odit adipisci itaque expedita. Distinctio maiores nisi eligendi facilis sed nulla consectetur rem fugiat, accusamus harum minus architecto sint omnis nam at, quas velit, voluptatem. Ratione nostrum asperiores illum ut ipsam reprehenderit quam! Consequatur soluta, tempore cupiditate magnam vel, eligendi placeat culpa labore officiis odit laudantium.</p>
		  	<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
	  	</div>
	  </div>
	</div>

</div>



@stop

@push ('script')
<script type="text/javascript" src="{!! admin_asset('vendor/jquery-datatable/jquery.dataTables.js') !!}"></script>
<script type="text/javascript" src="{!! admin_asset('vendor/jquery-datatable/extensions/export/dataTables.buttons.min.js') !!}"></script>
<script type="text/javascript" src="{!! admin_asset('vendor/jquery-datatable/extensions/export/buttons.html5.min.js') !!}"></script>
<script type="text/javascript" src="{!! admin_asset('vendor/jquery-datatable/extensions/export/buttons.flash.min.js') !!}"></script>
<script type="text/javascript" src="{!! admin_asset('vendor/jquery-datatable/extensions/export/jszip.min.js') !!}"></script>
<script type="text/javascript" src="{!! admin_asset('vendor/jquery-datatable/extensions/export/pdfmake.min.js') !!}"></script>
<script>
$(function(){
	$(".data-table").dataTable();
});
</script>
@endpush