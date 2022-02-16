@extends('layouts.app')
@section('title', 'Gnerals - Settings')
@section('content')
<div class="col-lg-12 mb-4 p-0">
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">General Settings</h6>
    </div>

    <div class="card-body">
      <div class="card-body">
        <form action="{{route('setting.general.index')}}" method="POST" enctype="multipart/form-data">
          @method('PUT')
          @csrf

          <div class="form-group">
            <label for="coinConversion"><b>Coin Conversion</b></label>
            <input min="0" type="number" name="coin_conversion" id="coinConversion" class="form-control" placeholder="Price of 1 coin" value="{{ $settings->coin_conversion }}" required>
          </div>

          <div class="form-group">
            <label for="companyRevenue"><b>Company Revenue (%)</b></label>
            <input min="0" max="100" type="number" name="company_revenue" class="form-control" placeholder="Company Revenue (%)" value="{{ $settings->company_revenue }}" required>
          </div>

          <div class="form-group">
            <label for="termsRules"><b>Terms & Rules</b></label>
            <textarea rows="10" name="terms_rules" id="termsRules" class="form-control" placeholder="Terms & Rules">{{ $settings->terms_rules }}</textarea>
          </div>

          <div class="form-group">
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection