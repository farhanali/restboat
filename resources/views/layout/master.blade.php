<!DOCTYPE html>
<html lang="en">

    @include('layout.head')

    <body>
        @include('layout.navbar')

        <div class="container">
            @include('flash::message')

            @yield('content')
        </div>

        @include('layout.footer')

        @yield('modals')

        @include('layout.scripts')
    </body>
</html>
