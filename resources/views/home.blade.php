@extends('layout.master')

@section('content')

    <div class="container-intro">
        <div class="intro">
            <div class="intro-text">
                <h1>
                    <strong>KEEP</strong> Requests <br>
                    and <strong>MOCK</strong> Responses
                </h1>
                <p>Get your RESTful mock server ready within minutes !<br/>Go on by signing with...</p>
                <div>
                    <a href="{{ route('user.login', 'google') }}" class="btn btn-google">Google</a>
                    <a href="{{ route('user.login', 'github') }}" class="btn btn-github">GitHub</a>
                </div>
            </div>
            <div class="preview">
                <img src="img/restboat-preview-new.png" alt="RestBoat Preview">
            </div>
        </div>
    </div>

    <div class="container-features">
        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                    <div class="feature-icon">
                        <i class="fa fa-bed"></i>
                    </div>
                    <h3 class="feature-title">RESTful</h3>
                    <p class="feature-description">
                        Fully compatible to REST architecture, supports GET, POST, PUT, PATCH and DELETE requests, also different content types such as json, xml and text
                    </p>
                </div>
                <div class="col-sm-4 bordered">
                    <div class="feature-icon">
                        <i class="fa fa-money"></i>
                    </div>
                    <h3 class="feature-title">FREE</h3>
                    <p class="feature-description">
                        RestBoat is completely free to use. We have put some <a href="#">limits for storing requests and logs</a> in data base to make sure the service running with less cost as possible.
                    </p>
                </div>
                <div class="col-sm-4">
                    <div class="feature-icon">
                        <i class="fa fa-github"></i>
                    </div>
                    <h3 class="feature-title">OPEN SOURCE</h3>
                    <p class="feature-description">
                        And Its Open Source !. Get the complete source code at <a href="#">GitHub</a>. Contribute yours to make it more power full, simple and bug free.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="container-functionality">
        <div class="container">
            <div class="row">
                <div class="col-md-4 preview-left">
                    <div class="functionality-preview">
                        <img src="img/request.png" alt="Request">
                    </div>
                </div>
                <div class="col-md-8">
                    <h4 class="functionality-subtitle">Keep Requests</h4>
                    <h2 class="functionality-title">Collect & Analyze Your Requests</h2>
                    <p class="functionality-description">
                        RestBoat provides you a mock server, which will collect and store every requests you made to it, and let you inspect them in an easy way.
                        Use RestBoat to analyze the requests send by your HTTP client and confirm those are as you expected. RestBoat is RESTful, it will support GET, POST, PUT, PATCH and DELETE methods.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="container-functionality">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-push-8 preview-right">
                    <div class="functionality-preview">
                        <img src="img/response.png" alt="Response">
                    </div>
                </div>
                <div class="col-md-8 col-md-pull-4">
                    <h4 class="functionality-subtitle">Mock Responses</h4>
                    <h2 class="functionality-title">Respond to your RESTful Api calls</h2>
                    <p class="functionality-description">
                        RestBoat makes it easy to mock your RESTful web services. You can simply define your web service URL's with a data to be responded.
                        RestBoat support different content types like application/json, application/xml, text/htl and text/plain. When ever your client application makes a request to the
                        desired URL, RestBoat serves the response you already set for that URL.
                    </p>
                </div>
            </div>
        </div>
    </div>
@stop
