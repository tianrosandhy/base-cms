<section once="footers" class="cid-rSZomT2eNK" id="footer7-n">
    <div class="container">
        <div class="media-container-row align-center mbr-white">
            <div class="row row-links">
                @if(SiteInstance::navigation()->structure('Footer'))
                <ul class="foot-menu">
                    @foreach(SiteInstance::navigation()->structure('Default') as $label => $data)
                    <li class="foot-menu-item mbr-fonts-style display-7">
                        <a class="text-white mbr-bold" href="{{ url($data['url']) }}" target="_blank">{{ $label}}</a>
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>
            <div class="row social-row">
                <div class="social-list align-right pb-2">
                    <?php
					$social_list = ['facebook', 'twitter', 'instagram', 'youtube', 'whatsapp'];
					?>
					@foreach($social_list as $soc)
					@if(setting('social.'.$soc))
                	<div class="soc-item">
                        <a href="{{ setting('social.'.$soc) }}" target="_blank">
                            <span class="socicon-{{ $soc }} socicon mbr-iconfont mbr-iconfont-social"></span>
                        </a>
                    </div>
					@endif
					@endforeach
                </div>
            </div>
            <div class="row row-copirayt">
                <p class="mbr-text mb-0 mbr-fonts-style mbr-white align-center display-7">
                    &copy; {{ date('Y') }} - All Rights Reserved
                </p>
            </div>
        </div>
    </div>
</section>
