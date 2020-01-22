@extends ('main::master')

@section ('content')
	<h3>{!! $title !!}</h3>
	<div class="card card-body">
		<div style="overflow-x:scroll; padding:1em 0;">
			<table class="table table-striped" id="datatable">
				<thead>
					<tr>
						<th>Name</th>
						<th>Path</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>	
		</div>
	</div>
	<div class="modal fade fill-in" id="form-modal" tabindex="-1" role="dialog" aria-hidden="true">
		<button type="button" title="Click to Dismiss" class="modal-custom-close" data-dismiss="modal" aria-hidden="true">
			&times;
		</button>
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-body default-modal-content">

				</div>
			</div>
		</div>
	</div>
@stop

@push ('script')
<script type="text/javascript" src="{!! admin_asset('vendor/jquery-datatable/jquery.dataTables.js') !!}"></script>
<script type="text/javascript">
var table_theme;
var page = 1;
$(document).ready(function() {
	table_theme = $('#datatable').DataTable({
		responsive: true,
		dom: 'lTtpi',
		processing: true,
		serverSide: true,
		ajax: {
			url: "{{ route('admin.themes.index') }}",
			dataType: "json",
			type: "POST",
			data: function (response) {
				key_search = {};
				$('.filter-field').each( function () {
					key = $(this).attr('name');
					val = $(this).val();
					key_search[key] = val;
				});
				return $.extend(false, {_token : CSRF_TOKEN}, key_search, response);
			}
		},
		"preDrawCallback": function(settings) {
			var api = this.api();
			page = parseInt(api.rows().page()) + 1;
      	},
		"drawCallback": function(settings) {
			$('[data-init-plugin="switchery"]').each(function() {
				var el = $(this);
				new Switchery(el.get(0), {
					size : el.data("size")
				});
			});
		},
		columns: [
			{data : 'name'},
			{data : 'path'},
			{data : 'status'},
		],
	});

	$('.filter-btn').on('click', function(){
		table_theme.draw();
	});

	$('body').on('change', 'input[name="themes-active"]', function(evt) {
		_theme = evt.currentTarget.dataset.theme;
		$.ajax({
			url : "{{ url()->route('admin.themes.set_active') }}",
			type : 'POST',
			dataType : 'json',
			data : {
				_token : window.CSRF_TOKEN,
				theme : _theme,
			},
			beforeSend: function() {
				$('#page-loader').show();
			},
			success : function(resp) {
				table_theme.draw();
				setTimeout(() => {
					$('#page-loader').hide();
				}, 1000);
			},
			error : function(resp){
				table_theme.draw();
				setTimeout(() => {
					$('#page-loader').hide();
				}, 1000);
			}
		});
	});
});
</script>
@endpush