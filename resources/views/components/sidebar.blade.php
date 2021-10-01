<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
      <div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-laugh-wink"></i>
      </div>
      <div class="sidebar-brand-text mx-3">SB Admin <sup>2</sup></div>
  </a>

  <!-- Divider -->
  <hr class="sidebar-divider my-0">

  <li class="nav-item {{ Route::is('home') }}">
      <a class="nav-link" href="{{ '/home' }}">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>{{ __('Dashboard') }}</span></a>
  </li>

  <!-- Divider -->
  <hr class="sidebar-divider">

  <div class="sidebar-heading">
      {{ __('Settings') }}
  </div>

  <li class="nav-item {{ Route::is('profile') }}">
      <a class="nav-link" href="{{'/profile' }}">
          <i class="fas fa-fw fa-user"></i>
          <span>{{ __('Profile') }}</span>
      </a>
  </li>

  <li class="nav-item {{ Route::is('about') }}">
      <a class="nav-link" href="{{ '/about' }}">
          <i class="fas fa-fw fa-hands-helping"></i>
          <span>{{ __('About') }}</span>
      </a>
  </li>

  <!-- Divider -->
  <hr class="sidebar-divider d-none d-md-block">

  <div class="text-center d-none d-md-inline">
      <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div>

</ul>