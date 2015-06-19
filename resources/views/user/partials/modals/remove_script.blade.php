<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.5/highlight.min.js"></script>
<script>
    hljs.initHighlightingOnLoad();

    $('.btn-remove').on('click', function(event) {
        event.preventDefault();

        var source       = $(this);
        var removeDialog = $('#remove-entry');

        removeDialog.find('form').attr('action', source.data('url'));
        removeDialog.find('.text-danger').text(source.data('path'));

        removeDialog.modal();
    });
</script>