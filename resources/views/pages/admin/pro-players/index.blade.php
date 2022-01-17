@extends('layouts.app')
@section('title', 'Pro Player Requests')
@section('content')
<div class="row">
	<x-dashboard.info-box title="Total Request" value="{{ $total['all'] }}" color="info" icon="fa-clipboard-list text-info"/>
  <x-dashboard.info-box title="Skill Approved" value="{{ $total['approved'] }}" color="success" icon="fa-check text-gray-300"/>
  <x-dashboard.info-box title="Skill Pending" value="{{ $total['pending'] }}" color="warning" icon="fa-clock text-gray-300"/>
  <x-dashboard.info-box title="Skill Rejected" value="{{ $total['rejected'] }}" color="danger" icon="fa-ban text-gray-300"/>
</div>
<div class="col-lg-12 mb-4 p-0">
	<div class="card shadow mb-4">
		<div class="card-header py-3 d-flex align-items-center">
			<h6 class="m-0 font-weight-bold text-primary">Pro Player Requests</h6>
			{{-- <a class="btn btn-primary ml-4" href="{{ route('pro-players.make.create') }}">Make a Pro Player</a> --}}
			<form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0" method="get" action="">
				<input class="form-control mr-sm-2" type="search" placeholder="Search" name="search"
					value="{{$search ?? ''}}" aria-label="Search">
				<button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Search</button>
			</form>
		</div>
		<div class="card-body table-responsive" style="min-height: 400px">

			<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">Large modal</button>

			<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">

						<!-- CAROUSEL IMAGE -->
						<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
							<ol class="carousel-indicators">
								<li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
								<li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
								<li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
							</ol>
							<div class="carousel-inner">
								<div class="carousel-item active">
									<img class="d-block w-100" src="..." alt="First slide">
								</div>
								<div class="carousel-item">
									<img class="d-block w-100" src="..." alt="Second slide">
								</div>
								<div class="carousel-item">
									<img class="d-block w-100" src="..." alt="Third slide">
								</div>
							</div>
							<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
								<span class="carousel-control-prev-icon" aria-hidden="true"></span>
								<span class="sr-only">Previous</span>
							</a>
							<a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
								<span class="carousel-control-next-icon" aria-hidden="true"></span>
								<span class="sr-only">Next</span>
							</a>
						</div>

					</div>
				</div>
			</div>
			

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
						$user 			= $proPlayer->player->user;
						$created 		= $proPlayer->created_at;
						$statusName 	= $proPlayer->status_name;
						$statusClass 	= 'font-weight-bold';

						switch($proPlayer->status) {
							case 0: $statusClass .= ' text-warning'; break;
							case 1: $statusClass .= ' text-danger'; break;
							case 2: $statusClass .= ' text-success'; break;
						}
					@endphp
					<tr>
						<td class="align-middle d-flex align-center">
							<img src="{{ StorageHelper::url($user->profile_photo)}}" alt="" width="70" class="mr-3 rounded">
							<div class="d-flex flex-column justify-content-center">
								<h6 class="m-0 font-weight-bold">{{ $user->name }}</h6>
								<small class="d-block">{{ $user->username }}</small>
							</div>
						</td>
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
							<a href="{{ route('pro-players.approve', [$proPlayer->id]) }}" class="btn btn-success {{ $proPlayer->status ? 'disabled' : '' }}" title="Approve"><i class="fas fa-check"></i></a>
							<a href="{{ route('pro-players.reject', [$proPlayer->id]) }}" class="btn btn-danger {{ $proPlayer->status ? 'disabled' : '' }}" title="Reject"><i class="fas fa-ban"></i></a>
						</td>
					</tr>
					@endforeach

				</tbody>
			</table>
			{{ $proPlayers->links() }}
		</div>
	</div>
</div>
@endsection