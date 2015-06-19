@extends('layout.master')

@section('head')
    <title>Restboat Home</title>
    <link href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.5/styles/tomorrow.min.css" rel="stylesheet">
@stop

@section('content')
    <div class="container-app">
        <div class="row">
            <!-- Left Column - Search Box and Request List -->
            <div class="col-md-4">

                <!-- Search Box -->
                @include('user.partials.list_search', ['route' => 'history.index'])

                <!-- Requests List -->
                <section class="requests-list">
                    @include('user.partials.list_toolbar', ['entries' => $logs->total()])

                    <!-- Listing request entries -->
                    <div class="requests">
                        @foreach($logs as $log)
                            <a class="{{ ($selectedLog && $selectedLog->id == $log->id) ? 'active' : '' }}"
                               href="{{ route('history.show', ['id' => $log->id,
                                    'page' => $logs->currentPage(), 'search' => Session::get('search')])}}">
                                <div>
                                    <span class="route">{{ $log->request->path }}</span>
                                    <button class="btn btn-link btn-remove" data-url="{{ route('history.destroy', $log->id) }}" data-path="{{ $log->request->path }}" >
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                                <div class="recent-log">
                                    {!! $log->requestMethod or '' !!}
                                    <span>{{ $log->created }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>

                <!-- Requests List Pagination -->
                <nav class="text-center">
                    {!! $logs->appends(['search' => Session::get('search')])->render() !!}
                </nav>
            </div>

            <!-- Right Column - Request Detail View -->
            <div class="col-md-8">
                @include('user.partials.request_log_details', ['request' => $selectedLog->request, 'log' => $selectedLog, 'response' => $response])
            </div>
        </div>
    </div>
@stop

@section('modals')
    @include('user.partials.modals.request_send')
    @include('user.partials.modals.remove_all', ['route' => 'history.destroy', 'title' => 'Remove all from history', 'message' => 'remove all request logs'])
    @include('user.partials.modals.remove_one', ['route' => 'history.destroy', 'title' => 'Remove log from history'])
    @include('user.partials.modals.response_update', ['request' => $selectedLog->request])
@stop

@section('script')
    @include('user.partials.modals.remove_script')
    @include('user.partials.modals.request_error_script')
@stop

