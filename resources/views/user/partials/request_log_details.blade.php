<section class="panel panel-plain request-details">
    <div class="panel-heading">
        <div class="panel-title">
            <span class="route">{{ $request->path }}</span>
        </div>

        <!-- Tabs for different request/response info -->
        <div class="panel-tabs">
            {!! $log->requestMethod or '' !!}
            <span>{{ $log->created or 'No recent log !' }}</span>
            <ul class="nav nav-tabs">
                @if($log)
                    @if($log->prettyType)
                        <li class="active">
                            <a href="#formatted-content" data-toggle="tab">{{ $log->prettyType }}</a>
                        </li>
                    @endif
                    <li class="{{ $log->prettyType ? '' : 'active' }}">
                        <a data-toggle="tab" href="#tab-raw">Raw</a>
                    </li>
                    <li><a data-toggle="tab" href="#tab-query">Query</a></li>
                    <li><a data-toggle="tab" href="#tab-header">Header</a></li>
                    <li><a data-toggle="tab" href="#tab-response">Response</a></li>
                @else
                    <li class="active">
                        <a data-toggle="tab" href="#tab-response">Response</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>

    <!-- Include all required tabs here tabs here -->
    <div class="panel-body tab-content">
        @if($log)
            @include('user.partials.tabs.pretty', ['selectedLog' => $log])
            @include('user.partials.tabs.raw', ['selectedLog' => $log,
                'inActive' => $log->prettyType ? '' : 'in active'])
            @include('user.partials.tabs.query', ['selectedLog' => $log])
            @include('user.partials.tabs.header', ['selectedLog' => $log])
            @include('user.partials.tabs.response', ['response' => $response,
                'inActive' => '', 'request' => $log->request])
        @else
            @include('user.partials.tabs.response', ['response' => $response,
                'inActive' => 'in active', 'request' => $request])
        @endif
    </div>
</section>