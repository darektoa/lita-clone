<div class="col-lg-12 mb-4 p-0">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">TopUp Histories</h6>
            <a class="btn btn-primary ml-4" href="{{ route('topup.index') }}">Top Up</a>
            <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0" method="get" action="">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" name="keyword"
                    value="{{$keyword ?? ''}}" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>

        <div class="card-body table-responsive" style="min-height: 400px">
            <table class="table table-hover " id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
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
                        $statusClass = 'font-weight-bold';

                        switch($purchase->status) {
                            case 0: $statusClass .= ' text-warning'; break;
                            case 1: $statusClass .= ' text-danger'; break;
                            case 2: $statusClass .= ' text-success'; break;
                        }
                    @endphp
                    <tr>
                        <td class="align-middle">{{ $purchase->coin->coin }}</td>
                        <td class="align-middle">{{ $purchase->coin->price }}</td>
                        <td class="align-middle {{ $statusClass }}">
                            {{ $purchase->statusName() }}
                        </td>
                        <td class="align-middle" style="white-space: nowrap">{{ $purchase->created_at->format('d-m-Y') }}</td>
                        <td class="align-middle">
                            <form action="{{ route('topup.destroy', [$purchase->id]) }}" method="POST">
                                @method('DELETE') @csrf
                                <button class="btn btn-danger swal-delete {{ $purchase->status ? 'disabled' : '' }}" title="Cancel"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $purchases->links() }}
        </div>
    </div>
</div>