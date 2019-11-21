@extends ('main::master')

@section ('content')

<a href="{{ route('admin.page.index') }}" class="btn btn-secondary btn-sm">Back to Data</a>

<h1 class="display-3" align="center">{{ $data->title }}</h1>

<div class="ket mb-2 mt-2" align="center">
	Created at <strong>{{ date('d M Y H:i:s', strtotime($data->created_at)) }}</strong>
</div>

@if($data->image)
<img src="{{ $data->getThumbnailUrl('image', 'large') }}" alt="{{ $data->title }}" style="width:100%;">
@endif

@if($data->description)
<div class="content card card-body">
	{!! $data->description !!}
</div>
@endif


@stop