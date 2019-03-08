@extends('layouts.bootstrap')

@section('content')
    <form  method="post" style="width: 800px; margin-left: 230px;">
        {{csrf_field()}}
        <h2 class="form-signin-heading" style="padding-left: 240px;"> 微信用户列表</h2>
        <table class="table table-bordered">
            <tr>
                <td>id</td>
                <td>名字</td>
                <td>关注时间</td>
                <td>操作</td>
            </tr>
            @foreach($arr as $v)
                <tr>
                    <td>{{$v['id']}}</td>
                    <td>{{$v['nickname']}}</td>
                    <td>{{date('Y-m-d H:i:s',$v['add_time'])}}</td>
                    <td>
                        @if($v['blacklist']==2)
                            <a href="/blacklist/{{$v['id']}}">加入黑名单</a>
                        @elseif($v['blacklist']==1)
                            <a href="" class="btn btn-success ">已加入黑名单</a>
                        @endif

                    </td>

                </tr>
            @endforeach
        </table>
    </form>
    {{ $arr->links() }}
@endsection

@section('footer')
    @parent
@endsection