@php
	$inputsAddGame = [
		[
			'id' 		=> 'game-name',
			'label' => 'Game Name:',
			'name'	=> 'name'
		],
		[
			'id' 		=> 'game-icon',
			'label' => 'Game Icon:',
			'name'	=> 'icon',
			'type' 	=> 'file'
		],
		[
			'id' 		=> 'game-base-price',
			'label' => 'Base Price:',
			'name'	=> 'base_price',
			'type' 	=> 'number'
		],
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
				action="{{ route('setting.games.store') }}"
				id="addGameModal"
				inputs="{!! json_encode($inputsAddGame) !!}"
				method="POST"
				title="Add Game"
			/>
			
			<x-modal-input 
				action="{{ route('setting.games.update', [1]) }}"
				id="editGameModal"
				inputs="{!! json_encode($inputsAddGame) !!}"
				method="PUT"
				title="Edit Game"
			/>

			<table class="table table-hover " id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
            			<th class="col-1">#</th>
						<th class="text-nowrap">Game</th>
						<th class="text-nowrap">Base Price</th>
						<th class="text-nowrap">Action</th>
					</tr>
				</thead>
				<tbody>
					
					@foreach ($games as $game)
					<tr>
						<td class="align-middle">{{ $loop->iteration }}</td>
						<td class="align-middle text-nowrap">
							<a href="{{ route('setting.games.show', [$game->id]) }}">
								<img src="{{ StorageHelper::url($game->icon) }}" alt="" width="70" class="mr-3 rounded">
								{{ $game->name }}
							</a>
						</td>
						<td class="align-middle text-nowrap">{{ $game->base_price }} Coin</td>
						<td class="align-middle text-nowrap" style="width: 82px">
							<button class="btn btn-warning edit-game" data-game="{{ $game }}" data-toggle="modal" data-target="#editGameModal">
								<i class="fas fa-edit" onclick=""></i>
							</button>
							<form action="{{ route('setting.games.destroy', [$game->id]) }}" method="POST" class="d-inline">
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

@section('scripts')
<script>
	const editGameButtons = document.querySelectorAll('button.edit-game');

	editGameButtons.forEach((item) => {
		item.addEventListener('click', () => {
			const editForm		= document.querySelector('#editGameModal form');
			const nameField 	= editForm.querySelector('#game-name');
			const priceField 	= editForm.querySelector('#game-base-price');
			const gameData 		= JSON.parse(item.dataset.game);
			const endpoint		= `{{ route('setting.games.update', ['']) }}/${gameData.id}`;
			editForm.action 	= endpoint;
			nameField.value 	= gameData.name;
			priceField.value 	= gameData.base_price;
		});
	});
</script>
@endsection