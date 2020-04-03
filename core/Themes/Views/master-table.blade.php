@extends ('main::master')

@section ('content')

<div class="header-box">
	<h3 class="display-4 mb-3">{!! $title !!}</h3>
	<div class="padd">
		<a href="{{ route('admin.theme-option.index') }}" class="btn btn-primary">Go to Theme Option</a>
	</div>
</div>

<div class="content-box">
	{!! $prepend_index !!}
	{!! $datatable->view() !!}
	{!! $append_index !!}
</div>

@stop

@push ('script')
@include ('main::assets.datatable', [
	'url' => url()->route('admin.'.$hint.'.datatable')
])
<script>
	$(".table-search-btn").on('click', function(){
		if($(this).hasClass('active')){
			$(this).removeClass('active');
			$(".table-search-filter").slideDown();
			$(this).find('span').html('Hide');
		}
		else{
			$(this).addClass('active');
			$(".table-search-filter").slideUp();
			$(this).find('span').html('Show');
		}
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
				showLoading();
			},
			success : function(resp) {
				tb_data.ajax.reload();
				setTimeout(() => {
					hideLoading();
				}, 500);
			},
			error : function(resp){
				tb_data.ajax.reload();
				setTimeout(() => {
					hideLoading();
				}, 500);
			}
		});
	});

</script>
@endpush