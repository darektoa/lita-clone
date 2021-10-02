@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
  @isset(Auth::user()->admin)
    @include('pages.admin.dashboard')
  @else
    @include('pages.user.dashboard')
  @endif
@endsection