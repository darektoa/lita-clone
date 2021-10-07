@php
  $coinNav = [
      'Pending'   => route('coins.index') . '?status=0', 
      'Failed'    => route('coins.index') . '?status=1',
      'Success'   => route('coins.index') . '?status=2',
      'Canceled'  => route('coins.index') . '?status=3'
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

  <x-sidebar.nav-item
    active="{{Request::is('withdrawal') }}"
    icon="fa-money-bill-wave"
    name="Withdrawal" 
    route="/withdrawal" />


  <x-divider mt mb="4"/>
  <x-sidebar.toggle/>
</ul>