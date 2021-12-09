<div class="col-lg-12 mb-4 p-0">
	<div class="card shadow mb-4">
		<div class="card-header py-3 d-flex align-items-center">
			<h6 class="m-0 font-weight-bold text-primary">Coin Transactions</h6>
			<form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0" method="get"
				action="">
				<input class="form-control mr-sm-2" type="search" placeholder="Search" name="keyword"
					value="{{$keyword ?? ''}}" aria-label="Search">
				<button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
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
						$receiver    = $transaction->receiver;
						$coin     	 = number_format($transaction->coin, 0, '.', ',');
						$created     = $transaction->created_at;
						$statusName  = ucfirst($transaction->status);
						$statusClass = 'font-weight-bold';

						switch($transaction->status) {
								case 'pending' 	: $statusClass .= ' text-warning'; break;
								case 'rejected'	: $statusClass .= ' text-danger'; break;
								case 'success'	: $statusClass .= ' text-success'; break;
						}
					@endphp
					<tr>
						<td class="align-middle">{{ $receiver->name }}</td>
						<td class="align-middle">{{ $coin }}</td>
						<td class="align-middle">{{ $transaction->type_name }}</td>
						<td class="align-middle" style="white-space: nowrap">
							<small class="d-block">{{ $created->format('d/m/Y') }}</small>
							<small class="d-block">{{ $created->format('H:i:s') }}</small>
						</td>
						<td class="align-middle {{ $statusClass }}" title="{{ $transaction->updated_at->format('d/m/Y H:i:s') }}">
							{{ $statusName }}

							@if($statusName != 'Pending')
							<small class="d-block">{{ $transaction->updated_at->format('d/m/Y') }}</small>
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