<div class="col-lg-12 mb-4 p-0">
	<div class="card shadow mb-4">
		<div class="card-header py-3 d-flex align-items-center">
			<h6 class="m-0 font-weight-bold text-primary">TopUp List</h6>
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
						<th>Price</th>
						<th>Status</th>
						<th>Date</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					
					@foreach ($purchases as $purchase)
					@php 
						$user = $purchase->player->user;
						$fullName = $user->first_name . ' ' .  $user->last_name;
					@endphp
					<tr>
						<td>{{ $fullName}}</td>
						<td>{{ $purchase->coin->coin }}</td>
						<td>{{ $purchase->coin->price }}</td>
						<td>{{ $purchase->created_at->format('d-m-Y') }}</td>
						<td>{{ $purchase->statusName() }}</td>
						<td style="white-space: nowrap; width: 82px">
							<a href="#" class="btn btn-success"><i class="fas fa-check"></i></a>
							<a href="#" class="btn btn-danger"><i class="fas fa-ban"></i></a>
						</td>
					</tr>
					@endforeach

				</tbody>
			</table>
		</div>
	</div>
</div>