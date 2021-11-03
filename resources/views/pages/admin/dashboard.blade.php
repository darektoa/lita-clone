<div class="row">
  <x-dashboard.info-box title="Users" value="{{ \App\Models\User::count() }}" color="info" icon="fa-users text-info"/>
  <x-dashboard.info-box title="Topup Approved" value="{{ $total['approved'] }}" color="success" icon="fa-check text-gray-300"/>
  <x-dashboard.info-box title="Topup Pending" value="{{ $total['pending'] }}" color="warning" icon="fa-clock text-gray-300"/>
  <x-dashboard.info-box title="Topup Rejected" value="{{ $total['rejected'] }}" color="danger" icon="fa-ban text-gray-300"/>

</div>{}