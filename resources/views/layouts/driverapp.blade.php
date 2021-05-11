<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Raleway" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

    <link rel="stylesheet" href="<?php echo asset('css/content.css') ?>" type="text/css">
    <link rel="stylesheet" href="{{asset('css/modal.css')}}">
    <link rel="stylesheet" href="{{asset('css/driver.css')}}">

    <link rel="stylesheet" href="<?php echo asset('css/nav.css') ?>" type="text/css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <title>DHP Delivery</title>
</head>

<body style="background: #E8E8E8;">


    @if(Session::has('message')) {!! Session::get('message') !!}@endif
    @yield('content')
</body>
<script src="{{asset('js/main.js')}}"></script>
<script type="text/javascript" src="{{ URL::asset('js/orderList.js') }}"></script>

</html>