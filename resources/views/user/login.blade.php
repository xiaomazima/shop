@extends('layouts.bootstrap')
@section('content')
<!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>用户登录</title>
</head>
<body>
<form action="/loginDo" method="post">
    {{csrf_field()}}
    <table  class="table table-bordered" style="width: 500px">
        <tr>
            <td>用户名：</td>
            <td> <input type="text" name="user_name"></td>
        </tr>
            <td>密码：</td>
            <td> <input type="password"  name="pwd"></td>
        </tr>
        <tr>
            <td></td>
            <td><button class="btn btn-primary">登录</button></td>
        </tr>


    </table>
</form>
</body>
</html>
@endsection