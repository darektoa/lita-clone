<div class="row">
  <x-dashboard.info-box title="Users" value="{{ \App\Models\User::count() }}" color="info" icon="fa-users"/>
  <x-dashboard.info-box title="Topup Approved" value="3" color="success" icon="fa-check"/>
  <x-dashboard.info-box title="Topup Pending" value="5" color="warning" icon="fa-clock"/>
  <x-dashboard.info-box title="Topup Rejected" value="1" color="danger" icon="fa-ban"/>

</div>