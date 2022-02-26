<div class="col-lg-12 mb-4 p-0">
	<div class="card shadow mb-4">
		<div class="card-header py-3 d-flex align-items-center">
			<h6 class="m-0 font-weight-bold text-primary">Coin Transactions</h6>
			<a href="{{ route('coins.send') }}" type="button" class="btn btn-primary ml-4">Send Coin</a>
			<form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0" method="get"
				action="">
				<input class="form-control mr-sm-2" type="search" placeholder="Search" name="search"
					value="{{request()->search ?? ''}}" aria-label="Search">
				<input type="hidden" name="type" value={{ request()->type ?? '' }}>
				<button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Search</button>
			</form>
		</div>
		<div class="card-body table-responsive" style="min-height: 400px">
			<table class="table table-hover " id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>Nama</th>
						<th>Coin</th>
						<th>Type</th>
						<th>Requested</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
					
					@foreach ($transactions as $transaction)
					@php 
						$type    	 = $transaction->type;
						$coin     	 = number_format($transaction->coin, 0, '.', ',');
						$createdAt   = $transaction->created_at->addHours(7);
						$updatedAt	 = $transaction->updated_at->addHours(7);
						$statusName  = ucfirst($transaction->status);
						$statusClass = 'font-weight-bold';
						$user 		 = $type === 1 ? $transaction->sender : $transaction->receiver;

						switch($transaction->status) {
								case 'pending' 	: $statusClass .= ' text-warning'; break;
								case 'rejected'	: $statusClass .= ' text-danger'; break;
								case 'success'	: $statusClass .= ' text-success'; break;
								case 'paid'		: $statusClass .= ' text-success'; break;
						}
					@endphp
					<tr>
						<td class="align-middle d-flex align-center">
							<img src="{{ StorageHelper::url($user->profile_photo) ?? asset('assets/images/icons/empty_profile.png')}}" alt="" width="70" class="mr-3 rounded">
							<div class="d-flex flex-column justify-content-center">
								<h6 class="m-0 font-weight-bold">{{ $user->name }}</h6>
								<small class="d-block">{{ $user->username }}</small>
								<small class="d-block">
									<a href="//api.whatsapp.com/send?phone={{ $user->phone }}" target="_blank"><u>{{ $user->phone }}</u></a>
								</small>
								<small class="d-block">{{ $user->email }}</small>
							</div>
						</td>
						<td class="align-middle">{{ $coin }}</td>
						<td class="align-middle">{{ $transaction->type_name }}</td>
						<td class="align-middle" style="white-space: nowrap">
							<small class="d-block">{{ $createdAt->format('d/m/Y') }}</small>
							<small class="d-block">{{ $createdAt->format('H:i:s') }}</small>
						</td>
						<td class="align-middle {{ $statusClass }}" title="{{ $updatedAt->format('d/m/Y H:i:s') }}">
							{{ $statusName }}

							@if($statusName != 'Pending')
							<small class="d-block">{{ $updatedAt->format('d/m/Y') }}</small>
							@endif

						</td>
					</tr>
					@endforeach

				</tbody>
			</table>
			{{ $transactions->links() }}
		</div>
	</div>
</div>