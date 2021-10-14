@php
	$inputsAddTier = [
		[
			'id' 		=> 'tier-name',
			'label' => 'Tier Name:',
			'name'	=> 'name'
		],
	];
	
  $inputsAddRole = [
		[
			'id' 		=> 'role-name',
			'label' => 'Role Name:',
			'name'	=> 'name'
		],
	];
@endphp
@extends('layouts.app')
@section('title', 'Game - Settings')
@section('content')
<div class="row mb-4 p-0">

  <!-- HEADER SECTION -->
  <div class="col-12 mb-3">
    <div class="card">
      <div class="card-header py-3 d-flex align-items-center">
        <h5 class="m-0 font-weight-bold text-primary">Mobile Legends</h5>
      </div>
    </div>
  </div>


  <!-- FIRST SECTION -->
  <div class="col-lg-6 mb-4">
    <div class="card shadow">
      <!-- Header Card -->
      <div class="card-header py-3 d-flex align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Tier List</h6>
        <button type="button" class="btn btn-primary ml-4" data-toggle="modal" data-target="#addTierModal">Add Tier</button>
      </div>

      <!-- Body Card -->
      <div class="card-body table-responsive" style="min-height: 400px">
        <x-modal-input 
          action="{{ route('setting.games.tiers.store', [$game->id]) }}"
          id="addTierModal"
          inputs="{!! json_encode($inputsAddTier) !!}"
          method="POST"
          title="Add Tier"
        />
  
        <table class="table table-hover " id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>#</th>
              <th>Tier</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            
            @foreach ($game->gameTiers as $gameTier)
            <tr>
              <td class="align-middle">{{ $loop->iteration }}</td>
              <td class="align-middle" style="white-space: nowrap">
                <a href="{{ route('setting.games.show', [$gameTier->id]) }}">
                  {{ $gameTier->name }}
                </a>
              </td>
              <td class="align-middle" style="white-space: nowrap; width: 82px">
                <form action="{{ route('topup.destroy', [$gameTier->id]) }}" method="POST">
                  @method('DELETE') @csrf
                  <button class="btn btn-danger swal-delete" title="Delete"><i class="fas fa-trash"></i></button>
                </form>
              </td>
            </tr>
            @endforeach
  
          </tbody>
        </table>
      </div>
    </div>
  </div>


  <!-- SECOND SECTION -->
  <div class="col-lg-6 mb-4">
    <div class="card shadow">
      <!-- Header Card -->
      <div class="card-header py-3 d-flex align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Role List</h6>
        <button type="button" class="btn btn-primary ml-4" data-toggle="modal" data-target="#addRoleModal">Add Role</button>
      </div>

      <!-- Body Card -->
      <div class="card-body table-responsive" style="min-height: 400px">
        <x-modal-input 
          action="{{ route('setting.games.roles.store', [$game->id]) }}"
          id="addRoleModal"
          inputs="{!! json_encode($inputsAddRole) !!}"
          method="POST"
          title="Add Role"
        />
  
        <table class="table table-hover " id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>#</th>
              <th>Role</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            
            @foreach ($game->gameRoles as $gameRole)
            <tr>
              <td class="align-middle">{{ $loop->iteration }}</td>
              <td class="align-middle" style="white-space: nowrap">
                <a href="{{ route('setting.games.show', [$gameRole->id]) }}">
                  {{ $gameRole->name }}
                </a>
              </td>
              <td class="align-middle" style="white-space: nowrap; width: 82px">
                <form action="{{ route('topup.destroy', [$gameRole->id]) }}" method="POST">
                  @method('DELETE') @csrf
                  <button class="btn btn-danger swal-delete" title="Delete"><i class="fas fa-trash"></i></button>
                </form>
              </td>
            </tr>
            @endforeach
  
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection