@extends('layout.master')

@section('head')
    <link href='//fonts.googleapis.com/css?family=Lato:100' rel='stylesheet' type='text/css'>
@stop

@section('content')
    <div class="container-app container-empty">
        <div class="row">
            <div class="col-sm-8 col-sm-push-2 text-center">
                <p>
                    <i class="fa fa-frown-o fa-5x"></i>
                </p>
                <p>
                    Your request history seems to be empty !
                    <br/><br/>
                    Please <strong><a data-toggle="modal" data-target="#send-request" href="#">make a request</a></strong> to
                    <strong>'mock.restboat.com/{{ Auth::user()->preferences->user_identifier }}/any-api-path'</strong>
                    <br/> or <br/>go to <strong><a href="{{ route('collections.index') }}">collections</a></strong>.
                </p>
            </div>
        </div>
    </div>
@stop

@section('modals')
    @include('user.partials.modals.request_send')
@stop

@section('script')
    @include('user.partials.modals.request_error_script')
@stop