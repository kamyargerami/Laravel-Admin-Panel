<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <meta name="description" content="@yield('description')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @yield('style')
</head>
<body>

<div class="page-wrapper chiller-theme toggled">
    @include('partials.sidebar')
    <div class="page-content">
        @include('partials.alerts')

        @yield('content')
    </div>
    @include('partials.default-modal')
</div>

<script src="{{ mix('js/app.js') }}"></script>
@yield('script')
</body>
</html>
