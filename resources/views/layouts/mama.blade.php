<html>
<head>
    <title>Lening-@yield('title')</title>
</head>
<body>
@section('header')
    <p style="color: blue">这是头部</p>
@show

<div class="container">
    @yield('content')

</div>

@section('footer')
    <p style="color: blue">这是脚部</p>
@show
</body>
</html>