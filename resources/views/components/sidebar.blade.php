@php
  $dashboardNav = [
      'Total' => route('dashboard'),
      'Today' => route('dashboard') . '?today=true',
  ];

  $coinNav = [
      'Topup'   => route('coins.index') . '?type=0', 
      'Order'   => route('coins.index') . '?type=1',
      'Refund'  => route('coins.index') . '?type=2',
      'Gift'    => route('coins.index') . '?type=3',
  ];
  
  $orderNav = [
      'All'       => route('orders.index'),
      'Pending'   => route('orders.index') . '?status=0',
      'Rejected'  => route('orders.index') . '?status=1',
      'Approved'  => route('orders.index') . '?status=2',
      'Canceled'  => route('orders.index') . '?status=3',
      'Ended'     => route('orders.index') . '?status=4',
      'Expired'   => route('orders.index') . '?status=5',
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

  <x-sidebar.nav-collapse-item
    active="{{ Request::is('dashboard') }}"
    icon="fa-tachometer-alt"
    name="Dashboard" 
    routes="{!! json_encode($dashboardNav) !!}" />

  <x-sidebar.nav-collapse-item
    active="{{ Request::is('coins') }}"
    icon="fa-coins"
    name="Coins"
    routes="{!! json_encode($coinNav) !!}" />

  <x-sidebar.nav-item
    active="{{ Request::is('withdraws') }}"
    icon="fa-money-bill-wave"
    name="Withdrawal" 
    route="{{ route('withdraws.index') }}" />
  
    <x-sidebar.nav-collapse-item
    active="{{ Request::is('orders') }}"
    icon="fa-shopping-cart"
    name="Orders" 
    routes="{!! json_encode($orderNav) !!}" />


  @isset(auth()->user()->admin)
  <x-sidebar.nav-collapse-item
    active="{{ Request::is('pro-players') }}"
    icon="fa-user-check"
    name="Pro Players"
    routes="{!! json_encode($proPlayerNav) !!}" />

  <x-sidebar.nav-item
    active="{{ Request::is('users') }}"
    icon="fa-users"
    name="Users" 
    route="{{ route('users.index') }}" />

  <x-sidebar.nav-item
    active="{{ Request::is('notifications') }}"
    icon="fa-bell"
    name="Notifications" 
    route="{{ route('notifications.index') }}" />
  @endisset


  <x-sidebar.nav-collapse-item
    active="{{ Request::is('setting') }}"
    icon="fa-cogs"
    name="Settings"
    routes="{!! json_encode($settingNav) !!}" />


  <x-divider mt mb="4"/>
  <x-sidebar.toggle/>
</ul>