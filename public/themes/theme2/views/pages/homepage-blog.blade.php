<section class="features18 popup-btn-cards cid-rSZnUa8DFp" id="features18-l">
    <?php
    $posts = SiteInstance::post()->homePreview(3);
    ?>
    @if($posts->count() > 0)
    <div class="container">
        <h2 class="mbr-section-title pb-3 align-center mbr-fonts-style display-2">
            Our Latest Blog
        </h2>

        <div class="media-container-row pt-5 ">
            <?php
            $col = 12 / $posts->count();
            ?>
            @foreach($posts as $row)
            <div class="card p-3 col-12 col-md-6 col-lg-{{ $col }}">
                <div class="card-wrapper">
                    <div class="card-img">
                        <div class="mbr-overlay"></div>
                        <div class="mbr-section-btn text-center">
                            <a href="{{ route('front.detail', ['slug' => $row->slug()]) }}" class="btn btn-primary display-4">Read More</a>
                        </div>
                        <img src="{{ $row->getThumbnailUrl('image', 'small') }}" alt="{{ $row->title }}">
                    </div>
                    <div class="card-box">
                        <h4 class="card-title mbr-fonts-style display-7">
                            {{ $row->title }}
                        </h4>
                        <p class="mbr-text mbr-fonts-style align-left display-7">
                            {{ $row->excerpt ? descriptionMaker($row->excerpt) : descriptionMaker($row->body, 15) }}
                        </p>
                        <a href="{{ route('front.detail', ['slug' => $row->slug()]) }}" class="btn btn-sm btn-block btn-primary">Read More</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</section>