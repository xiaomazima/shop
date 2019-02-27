@extends('layouts.bootstrap')

@section('content')


    <input type="hidden" value="{{$code_url}}" id="url">
    <input type="hidden" value="{{$order_sn}}" id="order_sn">
    <div id="code">

    </div>




@endsection

<script src="{{URL::asset('/js/jquery-1.12.4.min.js')}}"></script>
<script src="{{URL::asset('/js/jquery-3.2.1.min.js')}}"></script>
<script>
    $(function(){
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
        var code_url = $('#url').val();
        var order_sn = $('#order_sn').val();
//        console.log(order_sn);
        $("#code").qrcode({
            render: "canvas", //table方式
            width: 200, //宽度
            height:200, //高度
            text:code_url //任意内容
        });
    });
    var clear =function(){
        $.post(
                "/success",
                {order_sn:order_sn},
                function(msg){
                  if(msg==1){
                      alert('支付成功');
                  }
                }
        )

    };
    //计时器
    var a =setInterval(function(){
        clear();
    },1000*3)
</script>
