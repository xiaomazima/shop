{{-- 购物车 --}}
@extends('layouts.bootstrap')

@section('content')
    <form  method="post" style="width: 600px; margin-left: 230px;">
        {{csrf_field()}}
        <h2 class="form-signin-heading" style="padding-left: 240px;">购物车 展示</h2>
        <table class="table table-bordered">
            <tr>
                <td>商品名称</td>
                <td>商品价格</td>
                <td>购买时间</td>
                <td>购买数量</td>
                <td>操作</td>
            </tr>
            @foreach($list as $v)
                <tr>
                    <td>{{$v['goods_name']}}</td>
                    <td>{{$v['price']/100}}</td>
                    <td>{{date('Y-m-d H:i:s',$v['add_time'])}}</td>
                    <td>{{$v['num']}}</td>
                    <td><a href="/cartDel/{{$v['goods_id']}}"class="btn btn-info ">删除</a>
                        <a href="/orderAdd" id="submit_order" class="btn btn-info "> 提交订单 </a>
                    </td>
                </tr>
            @endforeach
        </table>
    </form>

@endsection

@section('footer')
    @parent
@endsection