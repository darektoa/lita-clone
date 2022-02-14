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
                            <input id="playerId" name="player_id" class="form-control" type="text" required>
                        </div>
    
                        <div class="form-group">
                            <label for="coin">Coin <span class="text-danger">*</span></label>
                            <input id="coin" name="coin" class="form-control" type="number" min="1" value="{{ old('coin') }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="type">Type <span class="text-danger">*</span></label>
                            <select id="type" name="type" class="form-control">
                                <option value="3">Gift</option>
                                <option value="0">Topup</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Description</label>
                            <input id="description" name="description" class="form-control" type="text" value="{{ old('description') }}">
                        </div>
                        
                        <div class="form-group">
                            <label for="price">Price</label>
                            <input id="price" class="form-control" type="text" readonly>
                        </div>
    
                        <button type="submit" class="btn btn-primary">Send</button>
                    </form>
                    
                    <div class="mt-4 col-lg-6 table-responsive">
                        <table class="table table-hover " id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Coin</th>
                                    <th>Role</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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
        const coins          = @json($coins);
        const coinConversion = @json($coinConversion);
        const token          = '{{ $loginToken->token }}';
    </script>
    <script src="{{ asset('js/scripts/pages/admin/coin/send.js') }}" type="module"></script>
@endsection