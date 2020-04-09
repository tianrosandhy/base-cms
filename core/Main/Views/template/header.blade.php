<!-- partial:partials/_navbar.html -->
<?php
$header_config = config('cms.admin.styling.header');
?>
<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row" style="@isset($header_config['background']) background:{{ $header_config['background'] }}; border-bottom-style:solid; @endisset @isset($header_config['line_color']) border-bottom-color:{{ $header_config['line_color']  }}; @endisset @isset($header_config['line_height']) border-bottom-width:{{ $header_config['line_height']  }}px; @endisset">
  <div class="navbar-brand-wrapper d-flex justify-content-center" style="background:transparent;">
    <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">  
      <a class="navbar-brand brand-logo" href="{{ admin_url('/') }}">
        @include ('main::template.components.logo')
      </a>
      <a class="navbar-brand brand-logo-mini" href="{{ admin_url('/') }}">
        @include ('main::template.components.logo')
      </a>
      <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
        <span class="mdi mdi-sort-variant" style="@isset($header_config['text_color']) color:{{ $header_config['text_color'] }}; @endisset"></span>
      </button>
    </div>  
  </div>
  <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
    <ul class="navbar-nav mr-lg-4 w-100">
      @if(config('cms.admin.components.search'))
        @include ('main::template.components.search')
      @endif
    </ul>
    <ul class="navbar-nav navbar-nav-right">
      @include ('main::template.components.language')
      @if(config('cms.admin.components.notification'))
        @include ('main::template.components.notification')
      @endif
      @if(config('cms.admin.components.userinfo'))
        @include ('main::template.components.userinfo')
      @endif
    </ul>
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
      <span class="mdi mdi-menu" style="@isset($header_config['text_color']) color:{{ $header_config['text_color'] }}; @endisset"></span>
    </button>
  </div>
</nav>