@php
  $inputsAddCoin = [
    [
      'id'    => 'coin-amount',
      'label' => 'Coin Amount',
      'name'  => 'coin'
    ],
    [
      'id'    => 'balance-amount',
      'label' => 'Price',
      'name'  => 'balance'
    ]
  ];
@endphp
@extends('layouts.app')
@section('title', 'Coins - Setting')
@section('content')
<div class="col-lg-12 mb-4 p-0">
	<div class="card shadow mb-4">
		<div class="card-header py-3 d-flex align-items-center">
			<h6 class="m-0 font-weight-bold text-primary">Coin List</h6>
			<button type="button" class="btn btn-primary ml-4" data-toggle="modal" data-target="#addCoinModal">Add Coin</button>
			<form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0" method="get"
				action="">
				<input class="form-control mr-sm-2" type="search" placeholder="Search" name="keyword"
					value="{{$keyword ?? ''}}" aria-label="Search">
				<button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
			</form>
		</div>
		<div class="card-body table-responsive" style="min-height: 400px">

			<x-modal-input 
				action="{{ route('setting.coins.store') }}"
				id="addCoinModal"
				inputs="{!! json_encode($inputsAddCoin) !!}"
				method="POST"
				title="Add Coin"
			/>

			<x-modal-input 
				action="{{ route('setting.coins.update', [1]) }}"
				id="editCoinModal"
				inputs="{!! json_encode($inputsAddCoin) !!}"
				method="PUT"
				title="Edit Coin"
			/>

			<table class="table table-hover " id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
            <th class="col-1">#</th>
						<th>Coin</th>
						<th>Price</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					
					@foreach ($coins as $coin)
					<tr>
						<td class="align-middle">{{ $loop->iteration }}</td>
						<td class="align-middle" style="white-space: nowrap">
              				{{ $coin->coin }}
						</td>
						<td class="align-middle" style="white-space: nowrap">
              				{{ number_format($coin->balance) }}
						</td>
						<td class="align-middle" style="white-space: nowrap; width: 82px">
							<button class="btn btn-warning edit-coin" data-coin="{{ $coin }}" data-toggle="modal" data-target="#editCoinModal">
								<i class="fas fa-edit" onclick=""></i>
							</button>
							<form action="{{ route('setting.coins.destroy', [$coin->id]) }}" method="POST" class="d-inline">
								@method('DELETE') @csrf
								<button class="btn btn-danger swal-delete" title="Delete"><i class="fas fa-trash"></i></button>
							</form>
						</td>
					</tr>
					@endforeach

				</tbody>
			</table>
			{{ $coins->links() }}
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script>
	const editCoinButtons = document.querySelectorAll('button.edit-coin');

	editCoinButtons.forEach((item) => {
		item.addEventListener('click', () => {
			const editForm			= document.querySelector('#editCoinModal form');
			const coinField 		= editForm.querySelector('#coin-amount');
			const balanceField	= editForm.querySelector('#balance-amount');
			const coinData 			= JSON.parse(item.dataset.coin);
			const endpoint			= `{{ route('setting.coins.update', ['']) }}/${coinData.id}`;
			editForm.action 		= endpoint;
			coinField.value 		= coinData.coin;
			balanceField.value 	= coinData.balance;
		});
	});
</script>
@endsection