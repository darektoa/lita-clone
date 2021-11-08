@extends('layouts.app')
@section('title', 'Gnerals - Settings')
@section('content')
<div class="col-lg-12 mb-4 p-0">

  <!-- GENERAL SETTING SECTION -->
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
            <button type="submit" class="btn btn-success">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>


  <!-- BANNER SETTING SECTION -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Banner Settings</h6>
    </div>

    <div class="card-body table-responsive" style="min-height: 400px">
      <div class="card-body">
        <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th class="col-1">#</th>
              <th class="col-4 text-nowrap">Banner</th>
              <th class="col-6 text-nowrap">Alt</th>
              <th class="text-nowrap">Action</th>
            </tr>
          </thead>
          <tbody>
            
            @foreach ($banners as $banner)
            <tr>
              <td class="align-middle">{{ $loop->iteration }}</td>
              <td class="align-middle text-nowrap">
                <img src="{{ $banner->url }}" alt="" class="mr-3 rounded">
              </td>
              <td class="align-middle">{{ $banner->alt }}</td>
              <td class="align-middle text-nowrap" style="width: 82px">
                <button class="btn btn-warning edit-banner" data-banner="{{ $banner }}" data-toggle="modal" data-target="#editbannerModal">
                  <i class="fas fa-edit" onclick=""></i>
                </button>
                <form action="{{ route('setting.games.destroy', [$banner->id]) }}" method="POST" class="d-inline">
                  @method('DELETE') @csrf
                  <button class="btn btn-danger swal-delete" title="Delete"><i class="fas fa-trash"></i></button>
                </form>
              </td>
            </tr>
            @endforeach
  
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection