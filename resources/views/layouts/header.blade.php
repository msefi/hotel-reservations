<header role="banner">
    <nav class="navbar navbar-expand-md navbar-dark bg-light">
    <div class="container">
        <a class="navbar-brand" href="/">Booking Hotel</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample05" aria-controls="navbarsExample05" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse navbar-light" id="navbarsExample05">
        <ul class="navbar-nav ml-auto pl-lg-5 pl-0">
            <li class="nav-item">
                <a class="nav-link active" href="/">Home</a>
            </li>
            @if(auth('customer')->check())
            <li class="nav-item">
                <a class="nav-link" href="/reservations">Reservations</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/myreservations">My Reservations</a>
            </li>
            @endif

            <li class="nav-item cta">
            @if(auth('customer')->check())
                <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><span>Logout</span></a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @else
                <a class="nav-link" href="{{ route('login') }}"><span>Login</span></a>
            @endif
            </li>
        </ul>
        
        </div>
    </div>
    </nav>
</header>