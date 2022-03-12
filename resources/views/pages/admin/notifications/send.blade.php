@extends('layouts.app')
@section('title', 'Notifications')
@section('head')
	<style>
		.ck-editor__editable{
            min-height: 200px;
			max-height: 400px;
		}
	</style>
@endsection
@section('content')
<div class="col-lg-12 mb-4 p-0">
	<div class="card shadow mb-4">
		<div class="card-header py-3 d-flex align-items-center">
			<h6 class="m-0 font-weight-bold text-primary">Send Notification</h6>
		</div>
		<div class="card-body table-responsive" style="min-height: 400px">
        
            <form action="{{ route('notifications.massive') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="form-group">
                    <label for="notif-title" class="col-form-label">Title</label>
                    <input id="notif-title" class="form-control" name="title" type="text" value="" placeholder="">       
                </div>
                <div class="form-group">
                    <label for="notif-body" class="col-form-label">Body</label>
                    <input id="notif-body" class="form-control" name="body" type="text">
                </div>
                <div class="form-group">
                    <label for="notif-history" class="col-form-label">History</label>
                    <select id="notif-history" class="form-control" name="history">
                        <option value="1">true</option>
                        <option value="0">false</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="notif-recipient" class="col-form-label">Recipient</label>
                    <select id="notif-recipient" class="form-control" name="recipient">
                        <option value="1">All</option>
                        <option value="2">Player</option>
                        <option value="3">Pro Player</option>
                    </select>
                </div>
            </form>

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
		const token = '{{ $loginToken->token }}';

		ClassicEditor.create(document.querySelector('#notif-body'), {
			ckfinder: {
				uploadUrl: `/api/media?token=${token}`
			}
		});
	</script>
@endsection