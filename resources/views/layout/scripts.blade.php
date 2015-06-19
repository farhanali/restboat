<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="/components/jquery/jquery-1.11.3.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="/components/bootstrap-3.3.4/js/bootstrap.min.js"></script>
<!--  For showing overlay messages by laracasts/flash package  -->
<script>
    $('#flash-overlay-modal').modal();
    $('div.alert').not('.alert-important').delay(3000).slideUp(300);
</script>
@yield('script')
