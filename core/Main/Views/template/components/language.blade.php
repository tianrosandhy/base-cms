@if(LanguageInstance::available())
<li class="nav-item dropdown mr-4">
  <a class="nav-link count-indicator dropdown-toggle d-flex align-items-center justify-content-center notification-dropdown" id="notificationDropdown" href="#" data-toggle="dropdown">
  	<img src="{{ LanguageInstance::active()['image'] }}" alt="{{ LanguageInstance::active()['title'] }}" style="height:30px;">
  </a>
  <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="notificationDropdown">
    <p class="mb-0 font-weight-normal float-left dropdown-header">Change Language</p>
  	@foreach(LanguageInstance::available(true) as $lang)
    <a class="dropdown-item" href="?lang={{ $lang['code'] }}">
      <div class="item-thumbnail">
      	<img src="{{ $lang['image'] }}" alt="{{ $lang['title'] }}" style="height:20px;">
      </div>
      <div class="item-content">
        <h6 style="padding:0 .5em" class="font-weight-normal">{{ $lang['title'] }}</h6>
      </div>
    </a>
    @endforeach
  </div>
</li> 
@endif