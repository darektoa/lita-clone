@extends('layouts.app')
@section('title', 'Send Coin')
@section('content')
<div class="row">
    <div class="col-lg-12 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Send Coin</h6>
            </div>

            <div class="card-body">
                <div class="row">
                    <form action="{{ route('coins.send.sendStore') }}" method="post" class="col-lg-6" enctype="multipart/form-data">
                        @csrf
    
                        <div class="form-group">
                            <label for="player_id">Player ID <span class="text-danger">*</span></label>
                            <input id="player_id" name="player_id" class="form-control" type="text" required>
                        </div>
    
                        <div class="form-group">
                            <label for="coin">Coin <span class="text-danger">*</span></label>
                            <input id="coin" name="coin" class="form-control" type="number" min="1" required>
                        </div>
    
                        <div class="form-group">
                            <label for="description">Description</label>
                            <input id="description" name="description" class="form-control" type="text">
                        </div>
    
                        <button type="submit" class="btn btn-primary">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- @section('scripts')
    <script>
        const coins = @json($coins);
    </script>
    <script src="{{ asset('js/scripts/pages/user/coin/topup.js') }}" type="module"></script>
@endsection --}}