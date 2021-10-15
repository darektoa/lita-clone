@extends('layouts.app')
@section('title', 'Pro Player Requests')
@section('content')
<div class="col-lg-12 mb-4 p-0">
	<div class="card shadow mb-4">
		<div class="card-header py-3 d-flex align-items-center">
			<h6 class="m-0 font-weight-bold text-primary">Pro Player Requests</h6>
			{{-- <a class="btn btn-primary ml-4" href="{{ route('pro-players.make.create') }}">Make a Pro Player</a> --}}
			<form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0" method="get"
				action="">
				<input class="form-control mr-sm-2" type="search" placeholder="Search" name="keyword"
					value="{{$keyword ?? ''}}" aria-label="Search">
				<button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
			</form>
		</div>
		<div class="card-body table-responsive" style="min-height: 400px">
			<table class="table table-hover " id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>Name</th>
						<th>Game</th>
						<th>Level</th>
						<th style="white-space: nowrap">Game Tier</th>
						<th style="white-space: nowrap">Game Roles</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					
					@foreach ($proPlayers as $proPlayer)
					@php 
						$user = $proPlayer->player->user;
						$created = $proPlayer->created_at;
						$fullName = $user->first_name . ' ' .  $user->last_name;
						$statusName = $proPlayer->statusName();
						$statusClass = 'font-weight-bold';

						switch($proPlayer->status) {
								case 0: $statusClass .= ' text-warning'; break;
								case 1: $statusClass .= ' text-danger'; break;
								case 2: $statusClass .= ' text-success'; break;
						}
					@endphp
					<tr>
						<td class="align-middle h6">{{ $fullName }}</td>
						<td class="align-middle h6">{{ $proPlayer->game->name }}</td>
						<td class="align-middle h6">{{ $proPlayer->game_level }}</td>
						<td class="align-middle h6">{{ $proPlayer->game_tier }}</td>
						<td class="align-middle h6">{{ $proPlayer->game_roles }}</td>
						<td class="align-middle {{ $statusClass }}" title="{{ $proPlayer->updated_at->format('d/m/Y H:i:s') }}">
							{{ $statusName }}

							@if($statusName != 'Pending')
							<small class="d-block">{{ $proPlayer->updated_at->format('d/m/Y') }}</small>
							@endif

						</td>
						<td class="align-middle" style="white-space: nowrap; width: 82px">
							<a href="{{ route('topup.approve', [$proPlayer->id]) }}" class="btn btn-success {{ $proPlayer->status ? 'disabled' : '' }}" title="Approve"><i class="fas fa-check"></i></a>
							<a href="{{ route('topup.reject', [$proPlayer->id]) }}" class="btn btn-danger {{ $proPlayer->status ? 'disabled' : '' }}" title="Reject"><i class="fas fa-ban"></i></a>
						</td>
					</tr>
					@endforeach

				</tbody>
			</table>
			{{-- {{ $proPlayers->links() }} --}}
		</div>
	</div>
</div>
@endsection