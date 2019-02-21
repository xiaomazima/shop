@extends('layouts.bootstrap')

@section('content')
    <div class="container">
        <h2 class="form-signin-heading" style="padding-left: 240px;">商品详情</h2>
        <h1>{{$goods->goods_name}}</h1>

        <span> 价格： {{$goods->price / 100}}</span>


        <form class="form-inline">
            <div class="form-group">
                <label class="sr-only" for="goods_num">Amount (in dollars)</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="goods_num" value="1">
                </div>
            </div>
            <input type="hidden" id="goods_id" value="{{$goods->goods_id}}">
            <button type="submit" class="btn btn-primary" id="add_cart_btn">加入购物车</button>
            <a href="/cart">查看购物车</a>
        </form>
    </div>


@endsection

@section('footer')
    @parent
    <script src="{{URL::asset('/js/goods/goods.js')}}"></script>
@endsection