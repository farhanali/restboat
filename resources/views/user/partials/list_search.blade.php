{!! Form::open(['route' => $route, 'method' => 'get', 'class' => 'search']) !!}
    <input type="search" name="search" value="{{ Session::get('search') }}" class="form-control" placeholder="Search Requests">
{!! Form::close() !!}