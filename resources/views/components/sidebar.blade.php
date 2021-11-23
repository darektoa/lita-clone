@php
  $coinNav = [
      'Pending'   => route('coins.index') . '?status=0', 
      'Failed'    => route('coins.index') . '?status=1',
      'Success'   => route('coins.index') . '?status=2',
      'Canceled'  => route('coins.index') . '?status=3'
  ];
  
  $proPlayerNav = [
      'Pending'   => route('pro-players.index') . '?status=0', 
      'Rejected'  => route('pro-players.index') . '?status=1',
      'Approved'  => route('pro-players.index') . '?status=2'
  ];

  $settingNav = [
    'Banners' => route('setting.banners.index'),
    'Coins'   => route('setting.coins.index'),
    'Faqs'    => route('setting.faqs.index'),
    'Games'   => route('setting.games.index'),
    'General' => route('setting.general.index'),
    'Genders' => route('setting.genders.index'),
    'Tiers'   => route('setting.tiers.index'),
  ];
@endphp

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled" id="accordionSidebar">
  <x-sidebar.brand 
    img="{{ asset('assets/images/brand_icons/48x48-transparent.png') }}"
    name="YUKITA"
    route="/dashboard" />

  <x-divider mt mb/>

  <x-sidebar.nav-item
    active="{{Request::is('dashboard') }}"
    icon="fa-tachometer-alt"
    name="Dashboard" 
    route="/dashboard" />

  <x-sidebar.nav-collapse-item
    active="{{Request::is('coins') }}"
    icon="fa-coins"
    name="Coins"
    routes="{!! json_encode($coinNav) !!}" />
  
  @isset(auth()->user()->admin)
  <x-sidebar.nav-collapse-item
    active="{{Request::is('pro-players') }}"
    icon="fa-users"
    name="Pro Players"
    routes="{!! json_encode($proPlayerNav) !!}" />
  @endisset

  <x-sidebar.nav-item
    active="{{Request::is('withdrawal') }}"
    icon="fa-money-bill-wave"
    name="Withdrawal" 
    route="/withdrawal" />

  <x-sidebar.nav-collapse-item
    active="{{Request::is('setting') }}"
    icon="fa-cogs"
    name="Settings"
    routes="{!! json_encode($settingNav) !!}" />


  <x-divider mt mb="4"/>
  <x-sidebar.toggle/>
</ul>