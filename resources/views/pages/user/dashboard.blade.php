<div class="row">
  <x-dashboard.info-box title="My Coins" value="{{ auth()->user()->player->coin }}" color="info" icon="fa-coins text-warning"/>
  <x-dashboard.info-box title="Topup Success" value="{{ $total['approved'] }}" color="success" icon="fa-check text-gray-300"/>
  <x-dashboard.info-box title="Topup Pending" value="{{ $total['pending'] }}" color="warning" icon="fa-clock text-gray-300"/>
  <x-dashboard.info-box title="Topup Failed" value="{{ $total['rejected'] }}" color="danger" icon="fa-ban text-gray-300"/>
</div>