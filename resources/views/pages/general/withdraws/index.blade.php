@extends('layouts.app')
@section('title', 'Withdraws')
@section('content')
  @isset(Auth::user()->admin)
    @include('pages.admin.withdraws.index')
  @else
    @include('pages.user.withdraws.index')
  @endif
@endsection