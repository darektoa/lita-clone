@extends('layouts.app')
@section('title', 'Pro Player Requests')
@section('content')
<div class="col-lg-12 mb-4 p-0">
	<div class="card shadow mb-4">
		<div class="card-header py-3 d-flex align-items-center">
			<h6 class="m-0 font-weight-bold text-primary">Orders</h6>
			<form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0" method="get" action="">
				<input class="form-control mr-sm-2" type="search" placeholder="Search" name="search"
					value="{{request()->search ?? ''}}" aria-label="Search">
				<button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Search</button>
			</form>
		</div>
		<div class="card-body table-responsive" style="min-height: 400px">
			<table class="table table-hover " id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>Player</th>
						<th style="white-space: nowrap">Play With</th>
						<th>Game</th>
						<th>Review</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
					
					@foreach ($orders as $order)
					@php 
						$user 			= $order->player->user;
                        $skill          = $order->proPlayerSkill;
                        $proPlayer      = $skill->player->user;
						$created 		= $order->created_at;
						$statusName 	= $order->status_name;
						$statusClass 	= 'font-weight-bold';

						switch($order->status) {
							case 0: $statusClass .= ' text-warning'; break;
							case 1: $statusClass .= ' text-danger'; break;
							case 2: $statusClass .= ' text-success'; break;
							case 3: $statusClass .= ' text-danger'; break;
						}
					@endphp
					<tr>
						<td class="align-middle d-flex align-center">
							<img src="{{ StorageHelper::url($user->profile_photo) ?? asset('assets/images/icons/empty_profile.png')}}" alt="" width="70" class="mr-3 rounded">
							<div class="d-flex flex-column justify-content-center">
								<h6 class="m-0 font-weight-bold">{{ $user->name }}</h6>
								<small class="d-block">{{ $user->username }}</small>
								<small class="d-block">{{ $user->email }}</small>
							</div>
						</td>
                        <td class="align-middle">
							<div class="d-flex flex-column justify-content-center">
								<h6 class="m-0 font-weight-bold">{{ $proPlayer->name }}</h6>
								<small class="d-block">{{ $proPlayer->username }}</small>
								<small class="d-block">{{ $proPlayer->email }}</small>
							</div>
						</td>
                        <td class="align-middle">
							<div class="d-flex flex-column justify-content-center">
								<h6 class="m-0 font-weight-bold">{{ $skill->game->name }}</h6>
								<small class="d-block">{{ $skill->game_tier }} (Lv. {{ $skill->game_level }})</small>
								<small class="d-block">{{ $skill->game_roles }}</small>
							</div>
						</td>
						<td class="align-middle {{ $statusClass }}" title="{{ $order->updated_at->format('d/m/Y H:i:s') }}">
							{{ $statusName }}
 
							@if($statusName != 'Pending')
							<small class="d-block">{{ $order->updated_at->format('d/m/Y') }}</small>
							@endif
						</td>
					</tr>
					@endforeach

				</tbody>
			</table>
			{{ $orders->links() }}
		</div>
	</div>
</div>
@endsection