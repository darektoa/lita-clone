<div class="row">
  <x-dashboard.info-box title="My Coins" value="{{ auth()->user()->player->coin }}" color="info" icon="fa-coins"/>
  <x-dashboard.info-box title="Topup Success" value="{{ $total['approved'] }}" color="success" icon="fa-check"/>
  <x-dashboard.info-box title="Topup Pending" value="{{ $total['pending'] }}" color="warning" icon="fa-clock"/>
  <x-dashboard.info-box title="Topup Failed" value="{{ $total['rejected'] }}" color="danger" icon="fa-ban"/>
</div>