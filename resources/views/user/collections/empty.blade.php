@extends('layout.master')

@section('head')
    <link href='//fonts.googleapis.com/css?family=Lato:100' rel='stylesheet' type='text/css'>
@stop

@section('content')
    <div class="container-empty">
        <h3>Your request collection is empty !</h3>
        <h4>It seems like you are a <strong>new user</strong> or you've removed all of your requests from collection.</h4>

        <hr/>

        <h4>Your mock server base url is: <code>http://mock.restboat.com/{{ Auth::user()->preferences->user_identifier }}/</code></h4>
        <p>To see RestBoat in action, send some requests to your mock server with any url slug you like. If you like, consider any of the following methods..</p>

        <div class="instruction-block">
            <h4>Send using cURL</h4>
            <blockquote>
                <code>
                    curl -X POST -d "{\"first_name\":\"John\",\"last_name\":\"Doe\",\"email\":\"john@example.com\"}"
                    http://mock.restboat.com/{{ Auth::user()->preferences->user_identifier }}/api/users
                    --header "Content-Type:application/json"
                </code>
            </blockquote>
            <h4>or</h4>
            <blockquote>
                <code>curl -X POST -d @filename.json
                    http://mock.restboat.com/{{ Auth::user()->preferences->user_identifier }}/api/users
                    --header "Content-Type:application/json"
                </code>
            </blockquote>
            <span class="help-block">This will read the contents of the file named <code>filename.json</code> and send it as the post request.</span>
        </div>

        <div class="instruction-block">
            <h4>Send directly from here</h4>
            <a class="btn btn-default" data-toggle="modal" data-target="#send-request" href="#" role="button">Send a request</a>
            <span class="help-block">Click here to send a request to your mock server using a form.</span>
            <h4>or</h4>
            <a class="btn btn-default" data-toggle="modal" data-target="#add-request" href="#" role="button">Define a request format</a>
            <span class="help-block">Click here to add a request format with a response to your collections.</span>
        </div>

        <div class="instruction-block">
            <h4>Use other apps or services</h4>
            <p>You may use any other application or services to send http requests, such as <a target="new" href="https://www.getpostman.com/">Postman</a>, <a target="new"
                        href="https://www.hurl.it/">Hurl.it</a> and <a target="new" href="http://requestmaker.com/">Request Maker</a> etc, or your own http clients.</p>
        </div>
    </div>
@stop

@section('modals')
    @include('user.partials.modals.request_add')
    @include('user.partials.modals.request_send')
@stop

@section('script')
    @include('user.partials.modals.request_error_script')
@stop
