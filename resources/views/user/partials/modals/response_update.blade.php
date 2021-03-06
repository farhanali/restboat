<!-- Dialog - Update response -->
<div class="modal fade" id="update-response" tabindex="-1">
    <div class="modal-dialog modal-lg">
        {!! Form::open(['route' => 'response.update', 'method' => 'put', 'class' => 'form-horizontal']) !!}
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal"><span>&times;</span></button>
                    <h4 class="modal-title">Update Response</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="request_id" value="{{ $request->id }}" />
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Status Code</label>
                        <div class="col-sm-5">
                            @include('user.partials.http_status_codes')
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Content Type</label>
                        <div class="col-sm-9">
                            <label class="radio-inline">
                                <input type="radio" name="content_type" value="application/json"
                                        {{ $response->content_type == 'application/json' ? 'checked' : ''}}>
                                JSON
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="content_type" value="application/xml"
                                        {{ $response->content_type == 'application/xml' ? 'checked' : ''}}>
                                XML
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="content_type" value="text/html"
                                        {{ $response->content_type == 'text/html' ? 'checked' : ''}}>
                                HTML
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="content_type" value="text/plain"
                                        {{ $response->content_type == 'text/plain' ? 'checked' : ''}}>
                                TEXT
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Response Content</label>
                        <div class="col-sm-9">
                            <textarea name="content" required="" rows="10" class="form-control">{{ $response->content }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
                    <button class="btn btn-success" type="submit">Save Changes</button>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
</div>
