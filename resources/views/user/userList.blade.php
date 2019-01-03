@extends('layouts.mama')
@section('title')
@section('content')
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>

<table border="1">
    <tr>
        <td>id</td>
        <td>姓名</td>
        <td>电话</td>
        <td>邮箱</td>
    </tr>
   @foreach($list as $v)
    <tr>
        <td>{{$v['id']}}</td>
        <td>{{$v['user_name']}}</td>
        <td>{{$v['phone']}}</td>
        <td>{{$v['email']}}</td>
    </tr>
 @endforeach
</table>

</body>
</html>
@endsection