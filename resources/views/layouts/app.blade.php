<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Raleway" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

    <link rel="stylesheet" href="<?php echo asset('css/content.css') ?>" type="text/css">
    <link rel="stylesheet" href="{{asset('css/modal.css')}}">

    <link rel="stylesheet" href="<?php echo asset('css/nav.css') ?>" type="text/css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <title>DHP Internal</title>
</head>

<body style="background: #E8E8E8;">

    <header>
        <div class="flex-container">
            <nav>

                <ul class="menu">
                    <li><img class="nav-logo" src="{{asset('image/logo.png')}}"></img></a></li>
                    <li class="logo"><a>Delta Hand Pie</a></li>

                    @if (Auth::check() && Auth::user()->role == 'operator')
                        <li class="item"><a href="{{ route('archive') }}">Archive</a></li>
                        <li class="item"><a href=" {{ route('list') }}">List</a></li>
                        <li class="item"><a href="{{ route('queue') }}">Queue</a></li>
                        <li class="item"><a href="{{route('delivering')}}">Delivery</a></li>
                        
                    @endif

                    <li style="visibility: hidden;  margin-left: auto;"></li>
                    
                    @if (Auth::check() && Auth::user()->role == 'operator')
                        <li class="item"><a href="{{ route('register') }}">Register User</a></li>
                    @endif
                    @auth
                    <li class="item2"><a href="{{ route('profile') }}" class="profileA"><button class="btn-profile" id="profileBtn">Profile</button></a>
                        {{-- <div id="profileID" class="profileModal">
                            <div class="profileModal-content">
                                <span class="close">&times;</span>
                                <p><?php echo Auth::user()->firstname?></p>
                            </div>
                        </div> --}}
                    </li>

                    {{--<li class="item2"><a href="#">
                        <form class="logoutButton" method="post" action="{{ route('logout') }}">
                            <button type="submit" class="btn-logout">Logout</button>
                            {{ csrf_field() }}
                        </form>
                    </a></li>
                    --}}
                    @endauth
                </ul>

            </nav>
        </div>

    </header>
    @if(Session::has('message')) {!! Session::get('message') !!}@endif
    @yield('content')
</body>
<script src="{{asset('js/main.js')}}"></script>
<script type="text/javascript" src="{{ URL::asset('js/orderList.js') }}"></script>

</html>