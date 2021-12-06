@extends('layouts.app')
@section('title', 'Profile')
@section('content')

@if ($errors->any())
<div class="alert alert-danger border-left-danger" role="alert">
    <ul class="pl-4 my-2">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="row">
  <div class="col-lg-4 order-lg-2">
    <div class="card shadow mb-4">
      <div class="card-profile-image mt-4">
        <figure class="rounded-circle avatar avatar font-weight-bold"
          style="font-size: 60px; height: 180px; width: 180px;" data-initial="{{ Auth::user()->name[0] }}">
        </figure>
      </div>

      <div class="card-body">
        <div class="row">
          <div class="col-lg-12">
            <div class="text-center">
              <h5 class="font-weight-bold">{{  Auth::user()->name }}</h5>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-8 order-lg-1">
    <div class="card shadow mb-4">
      <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">My Account</h6>
      </div>

      <div class="card-body">
        <form method="POST" action="{{ route('profile.update') }}" autocomplete="off">
          @csrf @method('PUT')

          <h6 class="heading-small text-muted mb-4">User information</h6>

          <div class="pl-lg-4">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group focused">
                        <label class="form-control-label" for="name">Name</label>
                        <input type="text" id="name" class="form-control" name="name" placeholder="Name" value="{{ old('name', Auth::user()->name) }}">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group focused">
                        <label class="form-control-label" for="username">Username</label>
                        <input type="text" id="username" class="form-control" name="username" placeholder="Username" value="{{ old('username', Auth::user()->username) }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="form-control-label" for="email">Email address</label>
                        <input type="email" id="email" class="form-control" name="email" placeholder="example@example.com" value="{{ old('email', Auth::user()->email) }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group focused">
                        <label class="form-control-label" for="current_password">Current password</label>
                        <input type="password" id="current_password" class="form-control" name="current_password" placeholder="Current password">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group focused">
                        <label class="form-control-label" for="new_password">New password</label>
                        <input type="password" id="new_password" class="form-control" name="new_password" placeholder="New password">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group focused">
                        <label class="form-control-label" for="confirm_password">Confirm password</label>
                        <input type="password" id="confirm_password" class="form-control" name="password_confirmation" placeholder="Confirm password">
                    </div>
                </div>
            </div>
          </div>

          <!-- Button -->
          <div class="mt-3 pl-lg-4">
            <div class="row">
              <div class="col">
                <button type="submit" class="btn btn-primary swal-success">Save Changes</button>
              </div>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
@endsection
