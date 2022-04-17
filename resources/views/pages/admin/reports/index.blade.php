@extends('layouts.app')
@section('title', 'reports')
@section('content')
<div class="col-lg-12 mb-4 p-0">
	<div class="card shadow mb-4">
		<div class="card-header py-3 d-flex align-items-center">
			<h6 class="m-0 font-weight-bold text-primary">Report List</h6>
			<form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0" method="get" action="">
				<input class="form-control mr-sm-2" type="search" placeholder="Search" name="search"
					value="{{$search ?? ''}}" aria-label="Search">
				<button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Search</button>
			</form>
		</div>
		<div class="card-body table-responsive" style="min-height: 400px">

			<table class="table table-hover " id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>Reporter</th>
						<th>Reported</th>
						<th>Service/Type</th>
						<th>Report</th>
					</tr>
				</thead>
				<tbody>
					
					@foreach ($reports as $report)
					@php
						$user			= $report->reporter;
						$reported		= $report->reportable;
						$typeNameClass	= 'font-weight-bold';
						$skill			= null;
                    	$createdAt		= $report->created_at->addHours(7);

						switch ($report->type) {
							case 1: $typeNameClass .= ' text-primary'; break;
							case 2:
								$game	 		= $reported->proPlayerSkill ?? null;
								$service  		= $reported->proPlayerService ?? null;
								$reported 		= $game->player->user ?? $service->player->user;
								$skill			= $game ?? $service;
								$typeNameClass .= ' text-warning'; break;
							case 3:
								$reported = $report->reportable->sender;
								$typeNameClass .= ' text-success'; break;
						}
					@endphp
					<tr>
						<td class="align-middle d-flex align-center">
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
						<td class="align-middle align-center">
							<div class="d-flex flex-column justify-content-center">
								<h6 class="m-0 font-weight-bold">{{ $reported->name }}</h6>
								<small class="d-block">{{ $reported->username }} ({{ $reported->gender->name ?? '??' }})</small>
								<small class="d-block">
									<a href="//api.whatsapp.com/send?phone={{ $reported->phone }}" target="_blank"><u>{{ $reported->phone }}</u></a>
								</small>
								<small class="d-block">{{ $reported->email }}</small>
							</div>
						</td>
						<td class="align-middle" style="white-space: nowrap">
							<div class="d-flex flex-column justify-content-center">
								<h6 class="m-0 {{ $typeNameClass }}">
									{{ $skill->game->name ?? $skill->service->name ?? $report->type_name }}
									@isset($skill->status)
									@if($skill->status === 3) <i class="fas fa-ban text-danger"></i> @endif
									@endisset
								</h6>
								@isset($skill->game)
								<small class="d-block">{{ $skill->game_tier }} (Lv. {{ $skill->game_level }})</small>
								<small class="d-block">{{ $skill->game_roles }}</small>
								@endif
							</div>
						</td>
						<td class="align-middle" style="white-space: nowrap">
							<h6 class="m-0"><b>{{ $createdAt->format('d/m/Y') }}</b> {{ $createdAt->format('H:i:s') }}</h6>
							<textarea 
								class="form-control"
								rows="2"
								style="resize: none; min-width: 200px"
								readonly>{{ $report->report ?? '' }}</textarea>
						</td>
					</tr>
					@endforeach

				</tbody>
			</table>
			{{ $reports->links() }}
		</div>
	</div>
</div>
@endsection