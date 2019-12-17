@if(is_admin_login())
<?php
$header_config = config('cms.admin.styling.header');
?>
<li class="nav-item nav-profile dropdown">
  <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
    <img src="{{ (strlen(admin_data('image')) == 0) ? admin_asset('img/default-user.png') : (ImageService::pathExists(admin_data('image')) ? storage_url(ImageService::getName(admin_data('image'), 'cropped')) : admin_asset('img/default-user.png')) }}" alt="profile"/>
    <span class="nav-profile-name" style="@isset($header_config['text_color']) color:{{ $header_config['text_color'] }}; @endisset">{{ admin_data('name') }}</span>
  </a>
  <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
    <a class="dropdown-item" href="{{ url('/') }}">
      <i class="mdi mdi-earth text-primary"></i>
      Go To Site
    </a>
    <a class="dropdown-item" href="{{ admin_url('my-profile') }}">
      <i class="mdi mdi-face-profile text-primary"></i>
      My Profile
    </a>
    <a class="dropdown-item" href="{{ admin_url('logout') }}">
      <i class="mdi mdi-logout text-primary"></i>
      Logout
    </a>
  </div>
</li>
@endif