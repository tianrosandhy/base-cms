    @if((isset($request['keyword']) || isset($request['category'])) && $data->count() > 0)
    <div class="alert alert-primary"><a href="#" class="reset-post-filter">Click here to reset search filter</a></div>
    @endif
    <div class="row">
        @foreach($data as $row)
        <div class="p-3 col-12 col-md-6 col-lg-4 post-image">
            <div class="card-wrapper">
                <div class="card-img">
                    <div class="category-holder position-relative">
                        @foreach($row->category as $cat)
                        <a href="{{ route('front.post-category', ['slug' => $cat->slug()]) }}" class="badge badge-primary">{{ $cat->name }}</a>
                        @endforeach
                    </div>
                    <a href="{{ route('front.detail', ['slug' => $row->slug()]) }}">
                        <img src="{{ $row->getThumbnailUrl('image', 'small') }}" alt="{{ $row->title }}">
                    </a>
                </div>
                <div class="card-box">
                    <h4 class="card-title my-3"><a href="{{ route('front.detail', ['slug' => $row->slug()]) }}">{{ $row->title }}</a></h4>
                    <p class="mbr-text mbr-fonts-style align-left display-7">
                        {{ $row->excerpt ? descriptionMaker($row->excerpt) : descriptionMaker($row->body, 15) }}
                    </p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @if($data->count() == 0)
    <div class="error-block py-5">
        <div class="row">
            <div class="col-lg-6">
                <h2>Oops..</h2>
                <p>Mohon maaf, belum ada postingan yang dapat ditampilkan. Ingin mencoba kata kunci yang lain?</p>
                <a class="btn btn-danger reset-post-filter">Reset</a>
            </div>
            <div class="col-lg-6 text-right">
                <i class="fa fa-exclamation-circle fa-5x"></i>
            </div>
        </div>
    </div>
    @endif

    <div class="py-4">
      {!! $data->links('include.pagination') !!}
    </div>
