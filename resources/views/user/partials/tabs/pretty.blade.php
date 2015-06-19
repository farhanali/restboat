@if($selectedLog->prettyType)
    <div id="formatted-content" class="tab-pane fade in active">
        {!! $selectedLog->prettyContent !!}
    </div>
@endif