<div class="tab-pane fade" id="tab-query">
    @if(empty($selectedLog->query_string))
        <strong>Query string is empty !</strong>
    @else
        {!! $selectedLog->parametersTable !!}
    @endif
</div>