<!-- partial:partials/_navbar.html -->
<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
  <div class="navbar-brand-wrapper d-flex justify-content-center">
    <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">  
      <a class="navbar-brand brand-logo" href="{{ admin_url('/') }}">
        @include ('main::template.components.logo')
      </a>
      <a class="navbar-brand brand-logo-mini" href="{{ admin_url('/') }}">
        @include ('main::template.components.logo')
      </a>
      <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
        <span class="mdi mdi-sort-variant"></span>
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
      @if(config('cms.admin.components.notification'))
        @include ('main::template.components.notification')
      @endif
      @if(config('cms.admin.components.userinfo'))
        @include ('main::template.components.userinfo')
      @endif
    </ul>
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
      <span class="mdi mdi-menu"></span>
    </button>
  </div>
</nav>