@php
  $inputsAddGender = [
    [
      'id'    => 'gender-name',
      'label' => 'Gender Name',
      'name'  => 'name'
    ]
  ];
@endphp
@extends('layouts.app')
@section('title', 'Genders - Setting')
@section('content')
<div class="col-lg-12 mb-4 p-0">
	<div class="card shadow mb-4">
		<div class="card-header py-3 d-flex align-items-center">
			<h6 class="m-0 font-weight-bold text-primary">Gender List</h6>
			<button type="button" class="btn btn-primary ml-4" data-toggle="modal" data-target="#addGenderModal">Add Gender</button>
			<form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0" method="get"
				action="">
				<input class="form-control mr-sm-2" type="search" placeholder="Search" name="keyword"
					value="{{$keyword ?? ''}}" aria-label="Search">
				<button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
			</form>
		</div>
		<div class="card-body table-responsive" style="min-height: 400px">

			<x-modal-input 
				action="{{ route('setting.genders.store') }}"
				id="addGenderModal"
				inputs="{!! json_encode($inputsAddGender) !!}"
				method="POST"
				title="Add Gender"
			/>

			<x-modal-input 
				action="{{ route('setting.genders.update', [1]) }}"
				id="editGenderModal"
				inputs="{!! json_encode($inputsAddGender) !!}"
				method="PUT"
				title="Edit Gender"
			/>

			<table class="table table-hover " id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
            <th class="col-1">#</th>
						<th>Gender</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					
					@foreach ($genders as $gender)
					<tr>
						<td class="align-middle">{{ $loop->iteration }}</td>
						<td class="align-middle" style="white-space: nowrap">
              {{ $gender->name }}
						</td>
						<td class="align-middle" style="white-space: nowrap; width: 82px">
							<button class="btn btn-warning edit-gender" data-gender="{{ $gender }}" data-toggle="modal" data-target="#editGenderModal">
								<i class="fas fa-edit" onclick=""></i>
							</button>
							<form action="{{ route('setting.genders.destroy', [$gender->id]) }}" method="POST" class="d-inline">
                @method('DELETE') @csrf
                <button class="btn btn-danger swal-delete" title="Delete"><i class="fas fa-trash"></i></button>
              </form>
						</td>
					</tr>
					@endforeach

				</tbody>
			</table>
			{{ $genders->links() }}
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script>
  const editGenderButtons = document.querySelectorAll('button.edit-gender');

  editGenderButtons.forEach((item) => {
    item.addEventListener('click', () => {
      const editForm	  = document.querySelector('#editGenderModal form');
      const nameField   = editForm.querySelector('#gender-name');
      const genderData 	= JSON.parse(item.dataset.gender);
      const endpoint	  = `{{ route('setting.genders.update', ['']) }}/${genderData.id}`;
      editForm.action   = endpoint;
      nameField.value   = genderData.name;
    });
  });
</script>
@endsection