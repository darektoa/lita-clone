<div class="row">
  <x-dashboard.info-box title="My Coins" value="{{ auth()->user()->player->coin }}" color="info" icon="fa-coins"/>
  <x-dashboard.info-box title="Topup Success" value="3" color="success" icon="fa-check"/>
  <x-dashboard.info-box title="Topup Pending" value="5" color="warning" icon="fa-clock"/>
  <x-dashboard.info-box title="Topup Failed" value="1" color="danger" icon="fa-ban"/>
</div>