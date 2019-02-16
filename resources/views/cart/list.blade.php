@extends('layouts.bootstrap')

@section('content')
    <form action="/goods_sou" method="get" style="width: 600px; margin-left: 230px;">
        {{csrf_field()}}
        <h2 class="form-signin-heading" style="padding-left: 240px;">商品 展示</h2>
        <td><input type="text" name="goods_name" placeholder="商品名称"></td>
        <td><input type="submit" name="sub" value="查询"></td>
        <table class="table table-bordered">

            <tr>
                <td>商品ID</td>
                <td>商品名称</td>
                <td>商品库存</td>
                <td>添加时间</td>
                <td>操作</td>
            </tr>
            @foreach($list as $v)
                <tr>
                    <td>{{$v['goods_id']}}</td>
                    <td>{{$v['goods_name']}}</td>
                    <td>{{$v['store']}}</td>
                    <td>{{date('Y-m-d H:i:s',$v['add_time'])}}</td>
                    <td><a href="/goodsList/{{$v['goods_id']}}">查看商品详情</a></td>
                </tr>
            @endforeach

        </table>
        {{$list->links()}}
    </form>
@endsection
