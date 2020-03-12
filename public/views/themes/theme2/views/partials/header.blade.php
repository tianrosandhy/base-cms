<section class="menu cid-qTkzRZLJNu" once="menu" id="menu1-0">

    <nav class="navbar navbar-expand beta-menu navbar-dropdown align-items-center navbar-fixed-top navbar-toggleable-sm">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
        </button>
        <div class="menu-logo">
            <div class="navbar-brand">
                <span class="navbar-logo">
                    <a href="{{ url('/') }}">
                         @include ('partials.header-logo')
                    </a>
                </span>
                <span class="navbar-caption-wrap">
                    <a class="navbar-caption text-white display-4" href="https://mobirise.com">
                        {{ strtoupper(setting('site.title')) }}
                    </a>
                </span>
            </div>
        </div>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            @include ('partials.header-navigation')
            @if(LanguageInstance::isActive())
            <?php
            $default_language = LanguageInstance::active();
            ?>
            <div class="navbar-buttons mbr-section-btn">
                <div class="dropdown">
                    <div class="dropdown">
                        <button class="btn btn-no-shadow dropdown-toggle btn-language" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="{{ $default_language['image'] }}" alt="{{ $default_language['title'] }}" style="height:50px;">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                            @foreach(LanguageInstance::available(true) as $lang)
                            <a class="dropdown-item" href="?lang={{ $lang['code'] }}">
                                <img src="{{ $lang['image'] }}" alt="{{ $lang['title'] }}" style="height:50px;">
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </nav>
</section>