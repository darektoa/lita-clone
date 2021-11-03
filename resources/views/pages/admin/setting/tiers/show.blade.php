@extends('layouts.app')
@section('title', 'Tier - Settings')
@section('content')
<div class="row mb-4 p-0">

  <!-- HEADER SECTION -->
  <div class="col-12 mb-3">
    <div class="card">
      <div class="card-header py-3 d-flex align-items-center">
        <a href="{{ route('setting.tiers.index') }}" class="m-0 h5"><i class="fas fa-arrow-circle-left"></i></a>
        <h5 class="m-0 ml-3 font-weight-bold text-primary">{{ $tier->name }}</h5>
      </div>
    </div>
  </div>

  <div class="col-lg-12 mb-4">
    <div class="card shadow">
      <!-- Header Card -->
      <div class="card-header py-3 d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Game List</h6>
        <b></b>
      </div>

      <!-- Body Card -->
      <div class="card-body table-responsive" style="min-height: 400px">  
        <table class="table table-hover " id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th class="col-1">#</th>
              <th class="text-nowrap">Game</th>
              <th class="text-nowrap">Base Price</th>
              <th class="text-nowrap">Price Now (+{{ $tier->price_increase }}%)</th>
              <th class="text-nowrap">Balance Now</th>
            </tr>
          </thead>
          <tbody>
            
            @foreach ($games as $game)
            @php
              $basePrice      = $game->base_price;
              $increasePrice  = $basePrice * ($tier->price_increase/100);
              $currentPrice   = $basePrice + $increasePrice;
              $currentBalance = $currentPrice * $appSetting->coin_conversion;
            @endphp
            <tr>
              <td class="align-middle text-nowrap">{{ $loop->iteration }}</td>
              <td class="align-middle text-nowrap">{{ $game->name }}</td>
              <td class="align-middle text-nowrap">{{ $basePrice }} Coin</td>
              <td class="align-middle text-nowrap">{{ $currentPrice }} Coin</td>
              <td class="align-middle text-nowrap">{{ number_format($currentBalance) }}</td>
            </tr>
            @endforeach
  
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-12 mb-2">
    {{ $games->links() }}
  </div>
</div>
@endsection