@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @if (Auth::user()->hasRole('affiliate'))
			@include('affiliate.cabinet.home')
		@endif
		<!--<div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    You are logged in!
                </div>
            </div>
        </div>-->
    </div>
</div>
@endsection
