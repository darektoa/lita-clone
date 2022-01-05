<div class="row">
  <x-dashboard.info-box title="Total Players" value="{{ $total->user->player }}" color="primary" icon="fa-users text-primary"/>
  <x-dashboard.info-box title="Total Order" value="{{ $total->proPlayerOrder->all }}" color="info" icon="fa-shopping-cart text-gray-300"/>
  <x-dashboard.info-box title="Total Topup" value="{{ $total->coinTransaction->paid }}" color="success" icon="fa-coins text-gray-300"/>
  <x-dashboard.info-box title="Total Withdraw" value="{{ $total->balanceTransaction->withdraw }}" color="warning" icon="fa-money-bill-wave-alt text-gray-300"/>
</div>

<div class="row">
    <div class="col-lg-6">
        <x-chart title="User Registration" canvasId="user-registration-chart" />
    </div>
    <div class="col-lg-6">
        <x-chart title="Player Order" canvasId="player-order-chart" />
    </div>
</div>


@section('scripts')
  <script type="module">
    import LineChart from '{{ asset('js/scripts/utils/LineChartHelper.js') }}';

    const userRegistration  = @json($chart->userRegistration);
    const playerOrder       = @json($chart->playerOrder);

    LineChart.init({
      label   : 'User Registration',
      canvasId: 'user-registration-chart',
      labels  : userRegistration.labels,
      data    : userRegistration.data,
    });  
    
    LineChart.init({
      label   : 'Player Order',
      canvasId: 'player-order-chart',
      labels  : playerOrder.labels,
      data    : playerOrder.data,
    });  
  </script>
@endsection