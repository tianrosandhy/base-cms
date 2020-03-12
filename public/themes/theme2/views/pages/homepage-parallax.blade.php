<section class="mbr-section info3 cid-rSZnQ4Eg32 mbr-parallax-background" id="info3-k">
    <div class="mbr-overlay" style="opacity: 0.8; background-color: rgb(35, 35, 35);">
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="media-container-column title col-12 col-md-10">
                <h2 class="align-left mbr-bold mbr-white pb-3 mbr-fonts-style display-2">
                    {{ themeoption('homepage.quick_contact.title') }}
                </h2>
                
                <p class="mbr-text align-left mbr-white mbr-fonts-style display-7">
                    {{ themeoption('homepage.quick_contact.description') }}
                </p>
                <div class="mbr-section-btn align-left py-4">
                    <?php
                    $btns = themeoption('homepage.quick_contact.button');
                    $n = count($btns['title']);
                    ?>
                    @for($i=0; $i<$n; $i++)
                    <a class="btn btn-{{ themeoption('type.'.$i, $btns) }} display-4 mr-2" href="{{ themeoption('url.'.$i, $btns) }}">{{ themeoption('title.'.$i, $btns) }}</a>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</section>