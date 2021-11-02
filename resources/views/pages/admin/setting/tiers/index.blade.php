@php
  $inputsAddTier = [
    [
      'id'    => 'tier-name',
      'label' => 'Tier Name',
      'name'  => 'name'
		], [
      'id'    => 'tier-price-increase',
      'label' => 'Price Increase (%)',
      'name'  => 'price_increase',
			'type'	=> 'number'
		], [
      'id'    => 'tier-minimum-Order',
      'label' => 'Minimum Order',
      'name'  => 'min_order',
			'type'	=> 'number'
		],
  ];
@endphp
@extends('layouts.app')
@section('title', 'Tiers - Setting')
@section('content')
<div class="col-lg-12 mb-4 p-0">
	<div class="card shadow mb-4">
		<div class="card-header py-3 d-flex align-items-center">
			<h6 class="m-0 font-weight-bold text-primary">Tier List</h6>
			<button type="button" class="btn btn-primary ml-4" data-toggle="modal" data-target="#addTierModal">Add Tier</button>
			<form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0" method="get"
				action="">
				<input class="form-control mr-sm-2" type="search" placeholder="Search" name="keyword"
					value="{{$keyword ?? ''}}" aria-label="Search">
				<button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
			</form>
		</div>
		<div class="card-body table-responsive" style="min-height: 400px">

			<x-modal-input 
				action="{{ route('setting.tiers.store') }}"
				id="addTierModal"
				inputs="{!! json_encode($inputsAddTier) !!}"
				method="POST"
				title="Add Tier"
			/>

			<x-modal-input 
				action="{{ route('setting.coins.update', [1]) }}"
				id="editTierModal"
				inputs="{!! json_encode($inputsAddTier) !!}"
				method="PUT"
				title="Edit Tier"
			/>

			<table class="table table-hover " id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
            <th class="col-1">#</th>
						<th >Tier</th>
						<th>Increase</th>
						<th class="text-nowrap">Min. Order</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					
					@foreach ($tiers as $tier)
					<tr>
						<td class="align-middle">{{ $loop->iteration }}</td>
						<td class="align-middle text-nowrap">{{ $tier->name }}</td>
						<td class="align-middle text-nowrap">{{ $tier->price_increase }}%</td>
						<td class="align-middle text-nowrap">{{ $tier->min_order }}</td>
						<td class="align-middle text-nowrap" style="width: 82px">
							<button class="btn btn-warning edit-tier" data-tier="{{ $tier }}" data-toggle="modal" data-target="#editTierModal">
								<i class="fas fa-edit" onclick=""></i>
							</button>
							<form action="{{ route('setting.tiers.destroy', [$tier->id]) }}" method="POST" class="d-inline">
                @method('DELETE') @csrf
                <button class="btn btn-danger swal-delete" title="Delete"><i class="fas fa-trash"></i></button>
              </form>
						</td>
					</tr>
					@endforeach

				</tbody>
			</table>
			{{ $tiers->links() }}
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script>
  const editTierButtons = document.querySelectorAll('button.edit-tier');

  editTierButtons.forEach((item) => {
    item.addEventListener('click', () => {
      const editForm	  = document.querySelector('#editTierModal form');
      const nameField   = editForm.querySelector('#tier-name');
      const tierData 	= JSON.parse(item.dataset.tier);
      const endpoint	  = `{{ route('setting.coins.update', ['']) }}/${tierData.id}`;
      editForm.action   = endpoint;
      nameField.value   = tierData.name;
    });
  });
</script>
@endsection