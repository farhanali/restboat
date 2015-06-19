<div class="tab-pane fade {{ $inActive }}" id="tab-response">
    <div class="table-responsive">
        <table class="table table-bordered">
            <tr>
                <th>Status Code</th>
                <td>{!! $response->status !!}</td>
                <th>Content Type</th>
                <td>{!! $response->contentType !!}</td>
            </tr>
            <tr>
                <th colspan="4">Content</th>
            </tr>
            <tr>
                <td colspan="4">
                    <pre class="pre-plain"><code class="{!! $response->prettyType !!}">{!! $response->prettyContent !!}</code></pre>
                </td>
            </tr>
        </table>
    </div>
    <div class="text-right">
        <button class="btn btn-default btn-response" type="button"
                data-toggle="modal" data-target="#update-response">
            Update Response
        </button>
    </div>
</div>