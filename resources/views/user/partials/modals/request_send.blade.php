<!-- Dialog - Send a Request -->
<div class="modal fade" id="send-request" tabindex="-1">
    <div class="modal-dialog modal-lg">
        {!! Form::open(['route' => 'history.store', 'class' => 'form-horizontal']) !!}
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal"><span>&times;</span></button>
                    <h4 class="modal-title">Send a Request</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group {{ $errors->first('path') ? 'has-error' : '' }}">
                        <label class="col-sm-3 control-label">Request Path</label>
                        <div class="col-sm-9">
                            <div class="col-sm-9 form-group">
                                @if($errors->first('path'))
                                    <span class="help-block">{{ $errors->first('path') }}</span>
                                @endif
                                <div class="input-group">
                                    <span class="input-group-addon">mock.restboat.com/{{ Auth::user()->preferences->user_identifier }}/</span>
                                    <input type="text" class="form-control" name="path" placeholder="api/v1/..">
                                    <span class="input-group-addon">/</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->first('method') ? 'has-error' : '' }}">
                        <label class="col-sm-3 control-label">Request Method</label>
                        <div class="col-sm-2">
                            <select class="form-control" name="method">
                                <option>POST</option>
                                <option>GET</option>
                                <option>PUT</option>
                                <option>DELETE</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->first('content_type') ? 'has-error' : '' }}">
                        <label class="col-sm-3 control-label">Content Type</label>
                        <div class="col-sm-9">
                            <label class="radio-inline">
                                <input type="radio" name="content_type" checked="" value="application/json">
                                JSON
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="content_type" value="application/xml">
                                XML
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="content_type" value="text/plain">
                                TEXT
                            </label>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->first('data') ? 'has-error' : '' }}">
                        <label class="col-sm-3 control-label">Raw Data</label>
                        <div class="col-sm-9">
                            <textarea name="data" rows="10" class="form-control" placeholder="Enter raw data appropriate to selected content type.."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
                    <button class="btn btn-success" type="submit">Send Request</button>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
</div>
