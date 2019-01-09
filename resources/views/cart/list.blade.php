@extends('layouts.bootstrap')

@section('content')
    <form action="/userlogin" method="post" style="width: 600px; margin-left: 230px;">
        {{csrf_field()}}
        <h2 class="form-signin-heading" style="padding-left: 240px;">List Show</h2>
        <table class="table table-bordered">
            <tr>
                <td>商品ID</td>
                <td>商品名称</td>
                <td>商品库存</td>
                <td>添加时间</td>
                <td>操作</td>
            </tr>
            @foreach($arr as $v)
                <tr>
                    <td>{{$v['goods_id']}}</td>
                    <td>{{$v['goods_name']}}</td>
                    <td>{{$v['store']}}</td>
                    <td>{{$v['add_time']}}</td>
                    <td><a href="/add/{{$v['goods_id']}}">加入购物车</a></td>
                </tr>
            @endforeach
        </table>
    </form>
@endsection