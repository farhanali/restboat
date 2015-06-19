@if($errors->first('path'))
    <script>
        $(function() {
            $({!! Request::is('collections*') ? '\'#add-request\'' : '\'#send-request\'' !!}).modal();
        });
    </script>
@endif