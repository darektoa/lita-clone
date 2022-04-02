@php
	$inputsAddService = [
		[
			'id' 	=> 'service-name',
			'label' => 'Service Name:',
			'name'	=> 'name'
		],
		[
			'id' 	=> 'service-icon',
			'label' => 'Service Icon:',
			'name'	=> 'icon',
			'type' 	=> 'file'
		],
		[
			'id' 	=> 'service-price',
			'label' => 'Price:',
			'name'	=> 'price',
			'type' 	=> 'number'
		],
		[
			'id' 	=> 'service-player-revenue',
			'label' => 'Player Revenue (%):',
			'name'	=> 'player_revenue',
			'type' 	=> 'number'
		],
	];
@endphp
@extends('layouts.app')
@section('title', 'Services - Setting')
@section('content')
<div class="col-lg-12 mb-4 p-0">
	<div class="card shadow mb-4">
		<div class="card-header py-3 d-flex align-items-center">
			<h6 class="m-0 font-weight-bold text-primary">Service List</h6>
			<form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0" method="get"
				action="">
				<input class="form-control mr-sm-2" type="search" placeholder="Search" name="keyword"
					value="{{$keyword ?? ''}}" aria-label="Search">
				<button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
			</form>
		</div>
		<div class="card-body table-responsive" style="min-height: 400px">

			<x-modal-input 
				action="{{ route('setting.services.update', [1]) }}"
				id="editServiceModal"
				inputs="{!! json_encode($inputsAddService) !!}"
				method="PUT"
				title="Edit Service"
			/>

			<table class="table table-hover " id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
            			<th class="col-1">#</th>
						<th class="text-nowrap">Service</th>
						<th class="text-nowrap">Price</th>
						<th class="text-nowrap">Player Revenue</th>
						<th class="text-nowrap">Action</th>
					</tr>
				</thead>
				<tbody>
					
					@foreach ($services as $service)
					<tr>
						<td class="align-middle">{{ $loop->iteration }}</td>
						<td class="align-middle text-nowrap">
							<img src="{{ StorageHelper::url($service->icon) ?? asset('assets/images/icons/empty_profile.png') }}" alt="" width="70" class="mr-3 rounded">
							{{ $service->name }}
						</td>
						<td class="align-middle text-nowrap">{{ $service->price }} Coin</td>
						<td class="align-middle text-nowrap">{{ $service->player_revenue }}%</td>
						<td class="align-middle text-nowrap" style="width: 82px">
							<button class="btn btn-warning edit-service" data-service="{{ $service }}" data-toggle="modal" data-target="#editServiceModal">
								<i class="fas fa-edit"></i>
							</button>
						</td>
					</tr>
					@endforeach

				</tbody>
			</table>
			{{ $services->links() }}
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script>
	const editServiceButtons = document.querySelectorAll('button.edit-service');

	editServiceButtons.forEach((item) => {
		item.addEventListener('click', () => {
			const editForm		= document.querySelector('#editServiceModal form');
			const nameField 	= editForm.querySelector('#service-name');
			const priceField 	= editForm.querySelector('#service-price');
			const revenueField 	= editForm.querySelector('#service-player-revenue');
			const serviceData 	= JSON.parse(item.dataset.service);
			const endpoint		= `{{ route('setting.services.update', ['']) }}/${serviceData.id}`;
			editForm.action 	= endpoint;
			nameField.value 	= serviceData.name;
			priceField.value 	= serviceData.price;
			revenueField.value 	= serviceData.player_revenue;
		});
	});
</script>
@endsection