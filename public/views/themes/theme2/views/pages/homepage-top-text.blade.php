<section class="counters1 counters cid-rSZnI1Zt2z" id="counters1-i">
    <div class="container">
        <div class="container pt-4 mt-2">
            <div class="media-container-row">
                <?php
                $lists = themeoption('homepage.summary.lists');
                $jml_row = count($lists['title']);
                $col = ceil(12 / $jml_row);
                ?>
                @for($i=0; $i<$jml_row; $i++)
                <div class="card p-3 align-center col-12 col-md-6 col-lg-4">
                    <div class="panel-item p-3">
                        <div class="card-img pb-3">
                            <span class="fa fa-{{ themeoption('icon.'.$i, $lists) }} mbr-iconfont"></span>
                        </div>

                        <div class="card-text">
                            <h4 class="mbr-content-title mbr-bold mbr-fonts-style display-7">
                                {{ themeoption('title.'.$i, $lists) }}
                            </h4>
                            <p class="mbr-content-text mbr-fonts-style display-7">
                                {{ themeoption('description.'.$i, $lists) }}
                            </p>
                        </div>
                    </div>
                </div>
                @endfor

            </div>
        </div>
   </div>
</section>