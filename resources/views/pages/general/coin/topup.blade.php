@extends('layouts.app')
@section('title', 'Coins')
@section('content')
  @isset(Auth::user()->admin)
    @include('pages.admin.coin.topup')
  @else
    @include('pages.user.coin.topup')
  @endif
@endsection