<div class="row">
  <x-dashboard.info-box title="Total Players" value="{{ $total->user->player }}" color="primary" icon="fa-users text-primary"/>
  <x-dashboard.info-box title="Total Order" value="{{ $total->proPlayerOrder->all }}" color="info" icon="fa-shopping-cart text-gray-300"/>
  <x-dashboard.info-box title="Total Topup" value="{{ $total->coinTransaction->paid }}" color="success" icon="fa-coins text-gray-300"/>
  <x-dashboard.info-box title="Total Withdraw" value="{{ $total->balanceTransaction->withdraw }}" color="warning" icon="fa-money-bill-wave-alt text-gray-300"/>
</div>