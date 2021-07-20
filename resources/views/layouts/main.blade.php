<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <meta name="description" content="@yield('description')">
    <meta name="keywords" content="@yield('keywords')">
    <meta name="author" content="Webrang">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{mix('/css/app.css')}}">
    <link rel="icon" type="image/png" href="/favicon.png">
    @yield('style')
</head>
<body>

<div class="container responsive-custom">
    @yield('content')
</div>

<script src="{{mix('/js/app.js')}}"></script>
@yield('script')
</body>
</html>

