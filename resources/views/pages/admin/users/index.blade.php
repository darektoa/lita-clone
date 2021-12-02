@extends('layouts.app')
@section('title', 'Users')
@section('content')
<div class="col-lg-12 mb-4 p-0">
	<div class="card shadow mb-4">
		<div class="card-header py-3 d-flex align-items-center">
			<h6 class="m-0 font-weight-bold text-primary">User List</h6>
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
						<th>Name</th>
						<th>Email</th>
						<th>Role</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					
					@foreach ($users as $user)
          @php
            $player     = $user->player;
            $admin      = $user->admin;
            $role       = $player ? ($player->is_pro_player ? 'Pro Player' : 'Player') : 'Admin';
            $roleClass  = 'font-weight-bold';

						switch($role) {
								case 'Admin'      : $roleClass .= ' text-success'; break;
								case 'Player'     : $roleClass .= ' text-warning'; break;
								case 'Pro Player' : $roleClass .= ' text-primary'; break;
						}
          @endphp
					<tr>
						<td class="align-middle" style="white-space: nowrap">
							{{ $user->name }}
							<small class="d-block">{{ $user->username }}</small>
						</td>
						<td class="align-middle">{{ $user->email }}</td>
						<td class="align-middle {{ $roleClass }}">{{ $role }}</td>
						<td class="align-middle" style="white-space: nowrap; width: 82px">
							<form action="{{ route('users.destroy', [$user->id]) }}" method="POST" class="d-inline">
                @method('DELETE') @csrf
                <button class="btn btn-danger swal-delete" title="Delete"><i class="fas fa-trash"></i></button>
              </form>
						</td>
					</tr>
					@endforeach

				</tbody>
			</table>
			{{ $users->links() }}
		</div>
	</div>
</div>
@endsection