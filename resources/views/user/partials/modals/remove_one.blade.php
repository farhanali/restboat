<!-- Remove Single Entry -->
<div class="modal fade" id="remove-entry" tabindex="-1">
    {!! Form::open(['route' => $route, 'method' => 'delete']) !!}
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal"><span>&times;</span></button>
                    <h4 class="modal-title">{{ $title }}</h4>
                </div>
                <div class="modal-body">
                    This action will remove <span class="text-danger">selected entry</span>. Are you sure?
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger" type="submit">Remove</button>
                </div>
            </div>
        </div>
    {!! Form::close() !!}
</div>