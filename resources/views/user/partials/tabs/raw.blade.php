<div class="tab-pane fade  {{ $inActive }}" id="tab-raw">
    @if(empty($selectedLog->content) || $selectedLog->content == '[]')
        <strong>Raw content is empty !</strong>
    @else
        <pre class="pre-bordered">{{ $selectedLog->content }}</pre>
    @endif
</div>