@extends('layouts.app')
@section('title', 'Notifications')
@section('content')
<div class="col-lg-12 mb-4 p-0">
	<div class="card shadow mb-4">
		<div class="card-header py-3 d-flex align-items-center">
			<h6 class="m-0 font-weight-bold text-primary">Notification List</h6>
			<a href="{{ route('notifications.sendView') }}" class="btn btn-primary ml-4">Send Notif</a>
			<form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0" method="get" action="">
				<input class="form-control mr-sm-2" type="search" placeholder="Search" name="search"
					value="{{$search ?? ''}}" aria-label="Search">
				<button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Search</button>
			</form>
		</div>
		<div class="card-body table-responsive" style="min-height: 400px">

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
								<small class="d-block">{{ strip_tags($notification->data->body) }}</small>
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