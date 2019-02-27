{{-- 购物车 --}}
@extends('layouts.bootstrap')

@section('content')
    <form  method="post" style="width: 800px; margin-left: 230px;">
        {{csrf_field()}}
        <h2 class="form-signin-heading" style="padding-left: 240px;"> 订单页面</h2>
        <table class="table table-bordered">
            <tr>
                <td>订单id</td>
                <td>订单编号</td>
                <td>购买时间</td>
                <td>总金额</td>
                <td>操作</td>
            </tr>
            @foreach($order_data as $v)
                <tr>
                    <td>{{$v['id']}}</td>
                    <td>{{$v['order_sn']}}</td>
                    <td>{{date('Y-m-d H:i:s',$v['add_time'])}}</td>
                    <td>￥{{$v['order_amount']/100}}</td>
                    <td>@if($v['is_pay']==1)
                            <a href="/pay/o/{{$v['order_sn']}}" class="btn btn-info ">结算</a>
                        @elseif($v['is_pay']==2)
                            <a href="" class="btn btn-success ">已结算|查看物流</a>
                        @endif
                        <a href="/weixin/pay/test/{{$v['order_sn']}}" class="btn btn-info" >微信支付</a>
                        <a href="/orderDel/{{$v['id']}}" class="btn btn-info" >取消订单</a>
                    </td>
                </tr>
            @endforeach
        </table>
    </form>

@endsection

@section('footer')
    @parent
@endsection