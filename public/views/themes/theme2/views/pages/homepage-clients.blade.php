<section class="clients cid-rSZnOfnHyV" data-interval="false" id="clients-j">
    <?php
    $clients = SiteInstance::client()->all();
    ?>
    @if($clients->count() > 0)
    <div class="container mb-5">
        <div class="media-container-row">
            <div class="col-12 align-center">
                <h2 class="mbr-section-title pb-3 mbr-fonts-style display-2">
                    Our Clients
                </h2>
                <h3 class="mbr-section-subtitle mbr-light mbr-fonts-style display-5">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quae officia hic aperiam rem, molestiae itaque.
                </h3>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="carousel slide" role="listbox" data-pause="true" data-keyboard="false" data-ride="carousel" data-interval="5000">
            <div class="carousel-inner" data-visible="5">
                @foreach($clients as $row)
                <div class="carousel-item ">
                    <div class="media-container-row">
                        <div class="col-md-12">
                            <div class="wrap-img">
                                <a href="#">
                                    <img src="{{ $row->getThumbnailUrl('image', 'small') }}" class="img-responsive clients-img">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="carousel-controls">
                <a data-app-prevent-settings="" class="carousel-control carousel-control-prev" role="button" data-slide="prev">
                    <span aria-hidden="true" class="mbri-left mbr-iconfont"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a data-app-prevent-settings="" class="carousel-control carousel-control-next" role="button" data-slide="next">
                    <span aria-hidden="true" class="mbri-right mbr-iconfont"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
    </div>
    @endif
</section>