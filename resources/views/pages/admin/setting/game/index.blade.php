@php
	$inputsAddGame = [
		['id' => 'game-name', 'label' => 'Game Name:'],
	];
@endphp
@extends('layouts.app')
@section('title', 'Games - Setting')
@section('content')
<div class="col-lg-12 mb-4 p-0">
	<div class="card shadow mb-4">
		<div class="card-header py-3 d-flex align-items-center">
			<h6 class="m-0 font-weight-bold text-primary">Game List</h6>
			<button type="button" class="btn btn-primary ml-4" data-toggle="modal" data-target="#addGameModal">Add Game</button>
			<form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0" method="get"
				action="">
				<input class="form-control mr-sm-2" type="search" placeholder="Search" name="keyword"
					value="{{$keyword ?? ''}}" aria-label="Search">
				<button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
			</form>
		</div>
		<div class="card-body table-responsive" style="min-height: 400px">

			<x-modal-input 
				action="{{ '/' }}"
				id="addGameModal"
				inputs="{!! json_encode($inputsAddGame) !!}"
				method="POST"
				title="Add Game"
			/>

			<table class="table table-hover " id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
            <th>#</th>
						<th>Game</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					
					@foreach ($games as $game)
					<tr>
						<td class="align-middle">{{ $loop->iteration }}</td>
						<td class="align-middle" style="white-space: nowrap">{{ $game->name }}</td>
						<td class="align-middle" style="white-space: nowrap; width: 82px">
							<form action="{{ route('topup.destroy', [$game->id]) }}" method="POST">
                @method('DELETE') @csrf
                <button class="btn btn-danger swal-delete" title="Delete"><i class="fas fa-trash"></i></button>
              </form>
						</td>
					</tr>
					@endforeach

				</tbody>
			</table>
			{{ $games->links() }}
		</div>
	</div>
</div>
@endsection