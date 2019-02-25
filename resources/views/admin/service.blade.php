
<h2><b style="color: red">开聊... 用户</b>:{{$name}}</h2>
<h2><img src="{{$img}}" alt=""></h2>

<div class="chat" id="chat_div">
@foreach($info as $v)
    <table>
        <tr>
            <td><h4>{{$name}}:</h4></td>
            <td>{{$v['message']}}</td>
        </tr>
    </table>
   @endforeach
</div>
<hr>

<form action="" class="form-inline">
    <input type="hidden" value="{{$img}}" id="openid">
    <input type="hidden" value="1" id="msg_pos">
    <textarea name="" id="send_msg" cols="100" rows="5"></textarea>
    <button class="btn btn-info" id="send_msg_btn">Send</button>
</form>