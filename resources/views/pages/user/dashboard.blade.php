<div class="row">
  <x-dashboard.info-box title="My Coins" value="{{ $total->coins->all }}" color="primary" icon="fa-coins text-primary"/>
  <x-dashboard.info-box title="Topup Success" value="{{ $total->coinReceivingTransaction->paid }}" color="success" icon="fa-check text-gray-300"/>
  <x-dashboard.info-box title="Topup Pending" value="{{ $total->coinReceivingTransaction->pending }}" color="warning" icon="fa-clock text-gray-300"/>
  <x-dashboard.info-box title="Topup Expired" value="{{ $total->coinReceivingTransaction->expired }}" color="danger" icon="fa-ban text-gray-300"/>
</div>