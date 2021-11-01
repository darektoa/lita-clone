@php
  $user       = auth()->user();
  $name       = $user->name ?? 'Name';
  $initial    = $name[0];
@endphp

<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
  <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
      <i class="fa fa-bars"></i>
  </button>

  <ul class="navbar-nav ml-auto">
    <div class="topbar-divider d-none d-sm-block"></div>

    <li class="nav-item dropdown no-arrow">
      <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ $name }}</span>
          <figure class="img-profile rounded-circle avatar font-weight-bold" data-initial="{{ $initial }}"></figure>
      </a>

      <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">

          @isset($user->player)
          <div class="dropdown-item" title="coins">
              <i class="fas fa-coins fa-sm fa-fw mr-2 text-warning"></i>
              {{ number_format(auth()->user()->player->coin) }}
          </div>
          @endisset

          <a class="dropdown-item" href="{{ '/profile' }}">
              <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
              Profile
          </a>

          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="{{ route('logout') }}">
              <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
              Logout
          </a>
      </div>
    </li>
  </ul>
</nav>