@extends('layout.master')

@section('head')
    <link href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.5/styles/tomorrow.min.css" rel="stylesheet">
@stop

@section('content')
    <div class="container-app">
        <div class="row">
            <!-- Left Column - Search Box and Request List -->
            <div class="col-md-4">

                <!-- Search Box -->
                @include('user.partials.list_search', ['route' => 'collections.index'])

                <!-- Requests List -->
                <section class="requests-list">
                    @include('user.partials.list_toolbar', ['entries' => $requests->total()])

                    <!-- Listing request entries -->
                    <div class="requests">
                        @foreach($requests as $request)
                            <a class="{{ ($selectedRequest && $selectedRequest->id == $request->id) ? 'active' : '' }}"
                               href="{{ route('collections.show', ['id' => $request->id,
                                    'page' => $requests->currentPage(), 'search' => Session::get('search')])}}">
                                <div>
                                    <span class="route">{{ $request->path }}</span>
                                    <button class="btn btn-link btn-remove" data-url="{{ route('collections.destroy', $request->id) }}" data-path="{{ $request->path }}" >
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                                <div class="recent-log">
                                    @if($request->recentLog)
                                        {!! $request->recentLog->requestMethod or '' !!}
                                        <span>{{ $request->recentLog->created }}</span>
                                    @else
                                        <span>No recent log !</span>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>

                <!-- Requests List Pagination -->
                <nav class="text-center">
                    {!! $requests->appends(['search' => Session::get('search')])->render() !!}
                </nav>
            </div>

            <!-- Right Column - Request Detail View -->
            <div class="col-md-8">
                @include('user.partials.request_log_details', ['request' => $selectedRequest, 'log' => $selectedRequest->recentLog, 'response' => $response])
            </div>
        </div>
    </div>
@stop

@section('modals')
    @include('user.partials.modals.request_add')
    @include('user.partials.modals.remove_all', ['route' => 'collections.destroy', 'title' => 'Remove all requests from collection', 'message' => 'remove all requests and request logs'])
    @include('user.partials.modals.remove_one', ['route' => 'collections.destroy', 'title' => 'Remove request from collection'])
    @include('user.partials.modals.response_update', ['request' => $selectedRequest])
@stop

@section('script')
    @include('user.partials.modals.remove_script')
    @include('user.partials.modals.request_error_script')
@stop

