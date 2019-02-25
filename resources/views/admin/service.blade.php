
<h2><b style="color: red">开聊... 用户</b>:{{$name}}</h2>
<h2><img src="{{$img}}" alt=""></h2>


<div style="float: right" id="right"></div>

<div class="chat" id="chat_div">
    <table>
        <thead id="show">
    @foreach($info as $v)

        <tr>
            <td><h3>{{$name}}:</h3></td>
            <td>{{$v['message']}}</td>
        </tr>

   @endforeach
        </thead>
    </table>
</div>
<hr>

    <input type="hidden" value="{{$openid}}" id="openid" >
    <textarea name="" id="send_msg" cols="100" rows="5"></textarea>
    <button class="btn btn-info" id="send_msg_btn">Send</button>


<script src="{{URL::asset('/js/jquery-1.12.4.min.js')}}"></script>

<script>
   $(function() {
       $('#send_msg_btn').click(function(msg){
           var text=$('#send_msg').val();
           var openid=$('#openid').val();
           $.post(
                   "touser",
                   {openid:openid,text:text},
                   function(msg){
                       if(msg=='发送成功'){
                           $('#right').append('<h3>'+text+':客服</h3>')
                           $('#send_msg').val('')
                       }
                   }
           )

       })

       var clear=function(){
           var openid=$('#openid').val();
           var _tr='';
           $.post(
                   "massage",
                   {openid:openid},
                   function(msg){
                       for(var i in msg['data']){
                           _tr+="<tr>" +
                                   "<td>"+msg['name']+"</td>" +
                                   "<td>"+msg['data'][i]['message']+"</td>" +
                                   "</tr>"
                       }
                       $('#show').html(_tr);
                   },'json'
           )
       };

       var a =setInterval(function(){
           clear();
       },1000*3)
   })
</script>
