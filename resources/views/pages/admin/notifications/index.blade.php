@php
	$inputsAddNotif = [
		[
			'id'    => 'notif-title',
			'label' => 'Title',
			'name'  => 'title',
		],
		[
			'id'        => 'notif-body',
			'label'     => 'Body',
			'name'      => 'body',
            'textarea'  => true,
		], 
		[
			'id'        => 'notif-history',
			'label'     => 'History',
			'name'      => 'history',
			'type'      => 'select',
			'options'   => [
                1 => 'true',
                0 => 'false',
            ],
		], 
		[
			'id'        => 'notif-recipient',
			'label'     => 'Recipient',
			'name'      => 'recipient',
			'type'      => 'select',
			'options'   => [
                1 => 'All',
                2 => 'Player',
                3 => 'Pro Player'
            ],
		], 
	];
@endphp
@extends('layouts.app')
@section('title', 'Notifications')
@section('head')
	<style>
		.ck-editor__editable{
			max-height: 240px;
		}
	</style>
@endsection
@section('content')
<div class="col-lg-12 mb-4 p-0">
	<div class="card shadow mb-4">
		<div class="card-header py-3 d-flex align-items-center">
			<h6 class="m-0 font-weight-bold text-primary">Notification List</h6>
			<button type="button" class="btn btn-primary ml-4" data-toggle="modal" data-target="#addNotifModal">Add Notif</button>
			<form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0" method="get" action="">
				<input class="form-control mr-sm-2" type="search" placeholder="Search" name="search"
					value="{{$search ?? ''}}" aria-label="Search">
				<button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Search</button>
			</form>
		</div>
		<div class="card-body table-responsive" style="min-height: 400px">

			<x-modal-input 
				action="{{ route('notifications.massive') }}"
				id="addNotifModal"
				inputs="{!! json_encode($inputsAddNotif) !!}"
				method="POST"
				title="Add Notif"
			/>

			<table class="table table-hover " id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>Notification</th>
						<th style="white-space: nowrap">Sent By</th>
						<th style="white-space: nowrap">Sent At</th>
						<th>Recipient</th>
					</tr>
				</thead>
				<tbody>
					
					@foreach ($notifications as $notification)
					@php
						$adminId 	= $notification->data->admin_id ?? 0;
						$sentBy		= $adminId ? App\Models\User::find($adminId)->name : 'Unknown';
                    	$createdAt	= $notification->created_at->addHours(7);
						$recipients = $notification->data->recipient ?? [];
					@endphp
					<tr>
						<td class="align-middle">
							<div class="d-flex flex-column justify-content-center">
								<h6 class="m-0 font-weight-bold">{{ $notification->data->title }}</h6>
								<small class="d-block">{{ $notification->data->body }}</small>
							</div>
						</td>
						<td class="align-middle" style="white-space: nowrap">
							<h6>{{ $sentBy }}</h6>
						</td>
                        <td class="align-middle">
                            <div class="d-flex flex-column justify-content-center">
								<h6 class="m-0 font-weight-bold">{{ $createdAt->format('d/m/Y') }}</h6>
								<small class="d-block"> {{ $createdAt->format('H:i:s') }}</small>
							</div>
                        </td>
                        <td class="align-middle" style="white-space: nowrap">

							@foreach($recipients as $recipient)
							@php
								$recipientClass = 'd-block font-weight-bold';

								switch($recipient) {
									case 'Player'	  : $recipientClass .= ' text-warning'; break;
									case 'Pro Player' : $recipientClass .= ' text-primary'; break;
								}
							@endphp
							<span class="{{ $recipientClass }}">{{ $recipient }}</span>
							@endforeach

						</td>
					</tr>
					@endforeach

				</tbody>
			</table>
			{{ $notifications->links() }}
		</div>
	</div>
</div>
@endsection

@php
    $loginToken = App\Models\LoginToken::firstOrCreate(
        ['user_id'  => auth()->id()],
        ['token'    => Hash::make(auth()->id())]
    );
@endphp

@section('scripts')
	<script>
		const token = '{{ $loginToken->token }}';

		ClassicEditor.create(document.querySelector('#notif-body'), {
			ckfinder: {
				uploadUrl: `/api/media?token=${token}`
			}
		});
	</script>
@endsection