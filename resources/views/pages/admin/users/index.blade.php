@php
	$proPlayer = [
		'total'	  => collect($total['proPlayer'])->sum(),
		'male'	  => $total['proPlayer'][1] ?? 0,
		'female'  => $total['proPlayer'][2] ?? 0,
	];

	$inputsAddAdmin = [
		[
			'id'    => 'user-name',
			'label' => 'Name',
			'name'  => 'name',
		],
		[
			'id'    => 'user-email',
			'label' => 'Email',
			'name'  => 'email',
		],
		[
			'id'    	=> 'user-password',
			'label' 	=> 'Default Password',
			'name'  	=> 'password',
			'value'		=> 'password',
			'readonly'	=> true
		], 
	];
@endphp
@extends('layouts.app')
@section('title', 'Users')
@section('content')
<div class="row">
	<x-dashboard.info-box title="Users" value="{{ $total['all'] }}" color="info" icon="fa-users text-info"/>
	<x-dashboard.info-box title="Admin" value="{{ $total['admin'] }}" color="success" icon="fa-user text-gray-300"/>
	<x-dashboard.info-box title="Player" value="{{ $total['player'] }}" color="warning" icon="fa-user text-gray-300"/>
	<x-dashboard.info-box title="Pro Player [M, F]" value="{{ $proPlayer['total'] }} [{{ $proPlayer['male'] }}, {{ $proPlayer['female'] }}]" color="primary" icon="fa-user text-gray-300"/>
</div>

<div class="col-lg-12 mb-4 p-0">
	<div class="card shadow mb-4">
		<div class="card-header py-3 d-flex align-items-center">
			<h6 class="m-0 font-weight-bold text-primary">User List</h6>
			<button type="button" class="btn btn-primary ml-4" data-toggle="modal" data-target="#addAdminModal">Add Admin</button>
			<form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0" method="get" action="">
				<input class="form-control mr-sm-2" type="search" placeholder="Search" name="search"
					value="{{$search ?? ''}}" aria-label="Search">
				<button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Search</button>
			</form>
		</div>
		<div class="card-body table-responsive" style="min-height: 400px">

			<x-modal-input 
				action="{{ route('users.storeAdmin') }}"
				id="addAdminModal"
				inputs="{!! json_encode($inputsAddAdmin) !!}"
				method="POST"
				title="Add Admin"
			/>

			<table class="table table-hover " id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>Name</th>
						<th>Coin</th>
						<th>Balance</th>
						<th>Role</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					
					@foreach ($users as $user)
					@php
						$player     = $user->player;
						$admin      = $user->admin;
						$role       = $player ? ($player->is_pro_player ? 'Pro Player' : 'Player') : 'Admin';
						$roleClass  = 'font-weight-bold';

						switch($role) {
								case 'Admin'      : $roleClass .= ' text-success'; break;
								case 'Player'     : $roleClass .= ' text-warning'; break;
								case 'Pro Player' : $roleClass .= ' text-primary'; break;
						}
					@endphp
					<tr>
						<td class="align-middle d-flex align-center" style="white-space: nowrap">
							<img src="{{ StorageHelper::url($user->profile_photo) ?? asset('assets/images/icons/empty_profile.png')}}" alt="" width="70" class="mr-3 rounded">
							<div class="d-flex flex-column justify-content-center">
							<div class="d-flex flex-column justify-content-center">
								<h6 class="m-0 font-weight-bold">{{ $user->name }}</h6>
								<small class="d-block">{{ $user->username }}</small>
								<small class="d-block">
									<a href="//wa.me/send?phone={{ $user->phone }}" target="_blank"><u>{{ $user->phone }}</u></a>
								</small>
								<small class="d-block">{{ $user->email }}</small>
							</div>
						</td>
						<td class="align-middle">{{ number_format($player->coin ?? 0) }}</td>
						<td class="align-middle">Rp {{ number_format($player->balance ?? 0) }}</td>
						<td class="align-middle {{ $roleClass }}">{{ $role }}</td>
						<td class="align-middle" style="white-space: nowrap; width: 82px">
							<form action="{{ route('users.destroy', [$user->id]) }}" method="POST" class="d-inline">
								@method('DELETE') @csrf
								<button class="btn btn-danger swal-delete" title="Delete"><i class="fas fa-trash"></i></button>
							</form>
						</td>
					</tr>
					@endforeach

				</tbody>
			</table>
			{{ $users->links() }}
		</div>
	</div>
</div>
@endsection