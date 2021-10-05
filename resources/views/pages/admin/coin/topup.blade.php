<div class="row">
    <div class="col-lg-12 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Top Up Coin</h6>
            </div>

            <div class="card-body">
                <div class="card-body table-responsive">
                    <form action="{{ route('topup.store') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="Jumlah Cuti">Player ID <span class="text-danger">*</span></label>
                            <input id="player_id" name="player_id" class="form-control" type="number" min="0" max="999999" required>
                        </div>

                        <div class="form-group">
                            <label for="Jumlah Cuti">Choose Coin <span class="text-danger">*</span></label>
                            <select id="coin" name="coin" class="form-control" required>
                              
                              @foreach($coins as $coin)
                              <option value="{{ $coin->id }}">{{ $coin->coin  }} Coin | {{ number_format($coin->price, 0) }}</option>
                              @endforeach

                            </select>   
                        </div>

                        <div class="form-group">
                            <label for="keterangan">Harga</label>
                            <input id="price" value="0" class="form-control" readonly/>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-success">Top Up</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    <script>
        const coins = @json($coins);
    </script>
    <script src="{{ asset('js/scripts/pages/user/coin/topup.js') }}" type="module"></script>
@endsection