<!-- partial:partials/_sidebar.html -->
<nav class="sidebar site-sidebar sidebar-offcanvas" id="sidebar" data-simplebar>
  <ul class="nav">
    @foreach(CMS::navigation() as $group => $data)
    <li class="nav-item {!! $data['active'] ? 'active' : '' !!}">
      <a href="{{ isset($data['submenu']) ? '#'. slugify($group) : $data['url'] }}" class="nav-link" {!! isset($data['submenu']) ? 'data-toggle="collapse" href="#'.slugify($group).'" aria-expanded="false" aria-controls="'.slugify($group).'"' : '' !!}>
        <i class="{{ $data['icon'] }}"></i>
        <span class="menu-title">{{ $group }}</span>
        @if(isset($data['submenu']))
        <i class="menu-arrow"></i>
        @endif
      </a>
      @if(isset($data['submenu']))
      <div class="collapse" id="{{ slugify($group) }}">
        <ul class="nav flex-column sub-menu">
          @foreach($data['submenu'] as $subgroup => $subdata)
          <li class="nav-item first-submenu {{ $subdata['active'] ? 'active' : '' }}">
            <a class="nav-link second-level" href="{{ $subdata['url'] }}"> {{ $subgroup }} {!! isset($subdata['submenu']) ? '<i class="menu-arrow"></i>' : '' !!}</a>
            @if(isset($subdata['submenu']))
            <ul class="ul-second-level">
              @foreach($subdata['submenu'] as $ssubgroup => $ssubdata)
              <li class="{{ $ssubdata['active'] ? 'active' : '' }}"><a href="{{ $ssubdata['url'] }}" class="menu-item">{{ $ssubgroup }}</a></li>
              @endforeach
            </ul>
            @endif
          </li>
          @endforeach
        </ul>
      </div>
      @endif
    </li>
    @endforeach
  </ul>
</nav>
<!-- partial -->