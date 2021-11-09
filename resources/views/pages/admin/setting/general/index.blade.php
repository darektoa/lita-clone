@php
  $inputsAddBanner = [
    [
      'id' 		=> 'banner-image',
			'label' => 'Banner Image:',
			'name'	=> 'image',
			'type' 	=> 'file'
		],
    [
      'id' 		=> 'banner-alt',
      'label' => 'Banner Alt:',
      'name'	=> 'alt'
    ],
		[
			'id' 		=> 'banner-link',
			'label' => 'Banner Link:',
			'name'	=> 'link',
		],
	];
@endphp
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
    <div class="card-header py-3 d-flex align-items-center">
      <h6 class="m-0 font-weight-bold text-primary">Banner List</h6>
      <button type="button" class="btn btn-primary ml-4" data-toggle="modal" data-target="#addBannerModal">Add Banner</button>
    </div>
    <div class="card-body table-responsive" style="min-height: 400px">

      <x-modal-input 
        action="{{ route('setting.banners.store') }}"
        id="addBannerModal"
        inputs="{!! json_encode($inputsAddBanner) !!}"
        method="POST"
        title="Add Banner"
      />
      
      <x-modal-input 
        action="{{ route('setting.banners.update', [1]) }}"
        id="editBannerModal"
        inputs="{!! json_encode($inputsAddBanner) !!}"
        method="PUT"
        title="Edit Banner"
      />

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
              <img src="{{ StorageHelper::url($banner->url) }}" alt="" class="w-100 mr-3 rounded">
            </td>
            <td class="align-middle">{{ $banner->alt }} </td>
            <td class="align-middle text-nowrap" style="width: 82px">
              <button class="btn btn-warning edit-banner" data-banner="{{ $banner }}" data-toggle="modal" data-target="#editBannerModal">
                <i class="fas fa-edit" onclick=""></i>
              </button>
              <form action="{{ route('setting.banners.destroy', [$banner->id]) }}" method="POST" class="d-inline">
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
@endsection

@section('scripts')
<script>
  const editBannerButtons = document.querySelectorAll('button.edit-banner');

  editBannerButtons.forEach((item) => {
    item.addEventListener('click', () => {
      const editForm		= document.querySelector('#editBannerModal form');
      const altField 	  = editForm.querySelector('#banner-alt');
      const linkField 	= editForm.querySelector('#banner-link');
      const bannerData 	= JSON.parse(item.dataset.banner);
      const endpoint		= `{{ route('setting.banners.update', ['']) }}/${bannerData.id}`;
      editForm.action 	= endpoint;
      altField.value 	  = bannerData.alt;
      linkField.value 	= bannerData.link;
    });
  });
</script>
@endsection