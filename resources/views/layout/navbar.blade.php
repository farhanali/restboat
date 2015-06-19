<nav class="navbar navbar-plain navbar-fixed-top">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed"
                    data-toggle="collapse" data-target="#nav-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ route('index') }}">RestBoat <small class="text-danger">beta</small></a>
        </div>

        @if(Auth::check())
            <div id="nav-collapse" class="collapse navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            {{ ucwords(Auth::user()->name) }} <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a tabindex="-1" href="{{ route('user.preferences') }}">Preferences</a></li>
                            <li><a tabindex="-1" href="{{ route('user.logout') }}">Logout</a></li>
                        </ul>
                    </li>
                    <li>
                        <img src="{{ Auth::user()->avatar }}" alt="" class="avatar">
                    </li>
                </ul>
            </div>
        @else
            <div id="nav-collapse" class="collapse navbar-collapse">
                <a href="https://github.com/farhanali/restboat" class="navbar-right navbar-link"><img src="img/github.png" alt="GitHub" class="avatar"></a>
            </div>
        @endif
    </div>
</nav>