@extends('layouts.app')
@section('title', 'Pro Player Requests')
@section('content')
<div class="row">
	<x-dashboard.info-box title="Skill Approved" value="{{ $total['approved'] }}" color="success" icon="fa-check text-success"/>
	<x-dashboard.info-box title="Skill Pending" value="{{ $total['pending'] }}" color="warning" icon="fa-clock text-gray-300"/>
	<x-dashboard.info-box title="Skill Rejected" value="{{ $total['rejected'] }}" color="danger" icon="fa-ban text-gray-300"/>
	<x-dashboard.info-box title="Skill Banned" value="{{ $total['banned'] }}" color="secondary" icon="fa-trash text-gray-300"/>
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

			<div id="detailModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="detailModal" aria-hidden="true">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">

						<!-- CAROUSEL IMAGE -->
						<div id="carouselDetail" class="carousel slide" data-ride="carousel">
							<ol class="carousel-indicators"></ol>
							<div class="carousel-inner"></div>
							<a class="carousel-control-prev" href="#carouselDetail" role="button" data-slide="prev">
								<span class="carousel-control-prev-icon" aria-hidden="true"></span>
								<span class="sr-only">Previous</span>
							</a>
							<a class="carousel-control-next" href="#carouselDetail" role="button" data-slide="next">
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
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					
					@foreach ($proPlayers as $proPlayer)
					@php 
						$user 			= $proPlayer->player->user;
						$createdAt 		= $proPlayer->created_at->addHours(7);
						$updatedAt 		= $proPlayer->updated_at->addHours(7);
						$status			= $proPlayer->status;
						$statusName 	= $proPlayer->status_name;
						$statusClass 	= 'font-weight-bold';

						switch($proPlayer->status) {
							case 0: $statusClass .= ' text-warning'; break;
							case 1: $statusClass .= ' text-danger'; break;
							case 2: $statusClass .= ' text-success'; break;
						}
					@endphp
					<tr>
						<td class="align-middle d-flex align-center" style="white-space: nowrap">
							<img src="{{ StorageHelper::url($user->profile_photo) ?? asset('assets/images/icons/empty_profile.png')}}" alt="" width="70" class="mr-3 rounded">
							<div class="d-flex flex-column justify-content-center">
								<h6 class="m-0 font-weight-bold">{{ $user->name }}</h6>
								<small class="d-block">{{ $user->username }} ({{ $user->gender->name ?? '??' }})</small>
								<small class="d-block">
									<a href="//api.whatsapp.com/send?phone={{ $user->phone }}" target="_blank"><u>{{ $user->phone }}</u></a>
								</small>
								<small class="d-block">{{ $user->email }}</small>
							</div>
						</td>
						<td class="align-middle" style="white-space: nowrap">
							<div class="d-flex flex-column justify-content-center">
								<h6 class="m-0 font-weight-bold">{{ $proPlayer->game->name }}</h6>
								<small class="d-block">{{ $proPlayer->game_tier }} (Lv. {{ $proPlayer->game_level }})</small>
								<small class="d-block">{{ $proPlayer->game_roles }}</small>
							</div>
						</td>
						<td class="align-middle {{ $statusClass }}" title="{{ $updatedAt->format('d/m/Y H:i:s') }}">
							{{ $statusName }}
 
							@if($statusName != 'Pending')
							<small class="d-block">{{ $updatedAt->format('d/m/Y') }}</small>
							@endif

						</td>
						<td class="align-middle" style="white-space: nowrap; width: 82px">
							<button type="button" class="btn btn-primary detail-player" data-player="{{ $proPlayer }}" data-toggle="modal" data-target="#detailModal">
								<i class="fas fa-eye"></i>
							</button>				
							
							@if($status === 2)
								<a href="{{ route('pro-players.ban', [$proPlayer->id]) }}" class="btn btn-danger" title="Ban"><i class="fas fa-ban"></i></a>
							@elseif($status === 3)
								<a href="{{ route('pro-players.unban', [$proPlayer->id]) }}" class="btn btn-success" title="Unban"><i class="fas fa-check"></i></a>
							@else
								<a href="{{ route('pro-players.approve', [$proPlayer->id]) }}" class="btn btn-success {{ $status ? 'disabled' : '' }}" title="Approve"><i class="fas fa-check"></i></a>
								<a href="{{ route('pro-players.reject', [$proPlayer->id]) }}" class="btn btn-danger {{ $status ? 'disabled' : '' }}" title="Reject"><i class="fas fa-ban"></i></a>
							@endif

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

@section('scripts')
<script>
	const detailButtons = document.querySelectorAll('button.detail-player');

	detailButtons.forEach((item) => {
		item.addEventListener('click', () => {
			const BASE_URL 			= '{{ StorageHelper::url("/") }}'.slice(0, -1);
			const detailModal 		= document.querySelector('#detailModal');
			const imagesElmnt		= detailModal.querySelector('.carousel-inner');
			const indicatorsElmnt 	= detailModal.querySelector('.carousel-indicators');
			const playerData 		= JSON.parse(item.dataset.player);
			
			imagesElmnt.innerHTML 	= '';
			indicatorsElmnt.innerHTML = '';
			playerData.pro_player_skill_screenshots.map((item, index) => {
				const imgElmnt 			= document.createElement('div');
				const indicatorElmnt 	= document.createElement('li');

				indicatorElmnt.dataset.target 	= '#carouselDetail';
				indicatorElmnt.dataset.slideTo 	= index;

				imgElmnt.classList.add('carousel-item');
				imgElmnt.innerHTML = `<img class="d-block w-100 mh-100" src="${BASE_URL}${item.url}" alt="" style="min-height: 25rem">`;

				if(index === 0){
					indicatorElmnt.classList.add('active');
					imgElmnt.classList.add('active');
				}

				indicatorsElmnt.appendChild(indicatorElmnt);
				imagesElmnt.appendChild(imgElmnt);

			});
		});
	});
</script>
@endsection