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
@section('content')
<div class="col-lg-12 mb-4 p-0">
	<div class="card shadow mb-4">
		<div class="card-header py-3 d-flex align-items-center">
			<h6 class="m-0 font-weight-bold text-primary">User List</h6>
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
						<th>Sent At</th>
						<th>Recipient</th>
					</tr>
				</thead>
				<tbody>
					
					@foreach ($notifications as $notification)
					@php
                        $createdAt	= $notification->created_at->addHours(7);
						$recipients = $notification->data->recipient ?? [];
					@endphp
					<tr>
						<td class="align-middle" style="white-space: nowrap">
							<div class="d-flex flex-column justify-content-center">
								<h6 class="m-0 font-weight-bold">{{ $notification->data->title }}</h6>
								<small class="d-block">{{ $notification->data->body }}</small>
							</div>
						</td>
                        <td class="align-middle h6">
                            <div class="d-flex flex-column justify-content-center">
								<h6 class="m-0 font-weight-bold">{{ $createdAt->format('d/m/Y') }}</h6>
								<small class="d-block"> {{ $createdAt->format('H:i:s') }}</small>
							</div>
                        </td>
                        <td class="align-middle">

							@foreach($recipients as $recipient)
							<span class="d-block">{{ $recipient }}</span>
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