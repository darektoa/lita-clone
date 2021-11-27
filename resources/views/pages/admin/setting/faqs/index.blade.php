@php
  $inputsAddFAQ = [
    [
      'id'    => 'FAQ-question',
      'label' => 'Question',
      'name'  => 'question'
		], [
      'id'    => 'FAQ-answer',
      'label' => 'Answer',
      'name'  => 'answer'
    ]
  ];
@endphp
@extends('layouts.app')
@section('title', 'FAQs - Setting')
@section('content')
<div class="col-lg-12 mb-4 p-0">
	<div class="card shadow mb-4">
		<div class="card-header py-3 d-flex align-items-center">
			<h6 class="m-0 font-weight-bold text-primary">FAQ List</h6>
			<button type="button" class="btn btn-primary ml-4" data-toggle="modal" data-target="#addFAQModal">Add FAQ</button>
			<form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0" method="get"
				action="">
				<input class="form-control mr-sm-2" type="search" placeholder="Search" name="keyword"
					value="{{$keyword ?? ''}}" aria-label="Search">
				<button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
			</form>
		</div>
		<div class="card-body table-responsive" style="min-height: 400px">

			<x-modal-input 
				action="{{ route('setting.faqs.store') }}"
				id="addFAQModal"
				inputs="{!! json_encode($inputsAddFAQ) !!}"
				method="POST"
				title="Add FAQ"
			/>

			<x-modal-input 
				action="{{ route('setting.faqs.update', [1]) }}"
				id="editFAQModal"
				inputs="{!! json_encode($inputsAddFAQ) !!}"
				method="PUT"
				title="Edit FAQ"
			/>

			<table class="table table-hover " id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
            <th class="col-1">#</th>
						<th>Question</th>
						<th>Answer</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					
					@foreach ($FAQs as $FAQ)
					<tr>
						<td class="align-middle">{{ $loop->iteration }}</td>
						<td class="align-middle">
              {{ $FAQ->question }}
						</td>
						<td class="align-middle">
              {{ $FAQ->answer }}
						</td>
						<td class="align-middle" style="white-space: nowrap; width: 82px">
							<button class="btn btn-warning edit-FAQ" data-faq="{{ $FAQ }}" data-toggle="modal" data-target="#editFAQModal">
								<i class="fas fa-edit" onclick=""></i>
							</button>
							<form action="{{ route('setting.faqs.update', [$FAQ->id]) }}" method="POST" class="d-inline">
                @method('DELETE') @csrf
                <button class="btn btn-danger swal-delete" title="Delete"><i class="fas fa-trash"></i></button>
              </form>
						</td>
					</tr>
					@endforeach

				</tbody>
			</table>
			{{ $FAQs->links() }}
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script>
  const editFAQButtons = document.querySelectorAll('button.edit-FAQ');

  editFAQButtons.forEach((item) => {
    item.addEventListener('click', () => {
      const editForm	  		= document.querySelector('#editFAQModal form');
      const questionField   = editForm.querySelector('#FAQ-question');
      const answerField   	= editForm.querySelector('#FAQ-answer');
      const FAQData 				= JSON.parse(item.dataset.faq);
      const endpoint	  		= `{{ route('setting.faqs.update', ['']) }}/${FAQData.id}`;
      editForm.action   		= endpoint;
      questionField.value   = FAQData.question;
      answerField.value   	= FAQData.answer;
    });
  });
</script>
@endsection