<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
  <x-sidebar.brand 
    img="{{ asset('assets/images/brand_icons/48x48-transparent.png') }}"
    name="LITA"
    route="/dashboard" />

  <x-divider mt mb/>

  <x-sidebar.nav-item
    active="{{Request::is('dashboard') }}"
    icon="fa-tachometer-alt"
    name="Dashboard" 
    route="/dashboard" />
  
  <x-sidebar.nav-item
    active="{{Request::is('coins') }}"
    icon="fa-coins"
    name="Coins" 
    route="/coins" />

  <x-sidebar.nav-item
    active="{{Request::is('withdrawal') }}"
    icon="fa-money-bill-wave"
    name="Withdrawal" 
    route="/withdrawal" />

  <x-divider mt mb="4"/>
  <x-sidebar.toggle/>
</ul>