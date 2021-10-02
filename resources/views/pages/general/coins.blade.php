@extends('layouts.app')
@section('title', 'Coins')
@section('content')
  @isset(Auth::user()->admin)
    @include('pages.admin.coins')
  @else
    @include('pages.user.coins')
  @endif
@endsection