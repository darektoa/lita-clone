@extends('layouts.app')
@section('title', 'Coins')
@section('content')
  @isset(Auth::user()->admin)
    @include('pages.admin.coin.index')
  @else
    @include('pages.user.coin.index')
  @endif
@endsection