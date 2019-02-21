@extends('layouts.bootstrap')
@section('content')
<form action="/registerDo" method="post">
    {{csrf_field()}}
<table  class="table table-bordered" style="width: 500px">
    <tr>
        <td>用户名：</td>
        <td> <input type="text" name="user_name"></td>
    </tr>
    <tr>
        <td> 手机号:</td>
        <td><input type="text" name="phone"></td>
    </tr>
    <tr>
        <td>  Email:</td>
        <td><input type="text" name="email"></td>
    </tr>
    <tr>
        <td>年龄：</td>
        <td><input type="text" name="age"></td>
    </tr>
    <tr>
        <td>密码：</td>
        <td> <input type="password"  name="pwd"></td>
    </tr>
    <tr>
        <td>确认密码：</td>
        <td> <input type="password"  name="pwd1"></td>
    </tr>
    <tr>
        <td></td>
        <td><button class="btn btn-danger">注册</button>
        </td>
    </tr>
</table>
</form>
@endsection