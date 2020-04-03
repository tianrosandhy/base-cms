<!DOCTYPE html>
<html lang="en">
<head>
  @include ('main::template.metadata')
</head>
<body {{ isset($as_ajax) ? 'body-ajax' : '' }}>
  <div id="page-loader">
    <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
  </div>

  <div class="success-handle">
    <i class="fa fa-check fa-fw"></i>
  </div>

  <div class="container-scroller">
    @include ('main::template.header')
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      @include ('main::template.sidebar')
      <div class="main-panel">
        <div class="content-wrapper">
          @yield ('content')
        </div>
        <!-- content-wrapper ends -->
        @include ('main::template.footer')
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  @include ('main::template.modal')
  @include ('main::template.alert-management')
  @include ('main::template.scripts')
</body>

</html>

