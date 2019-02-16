<?php

namespace App\Http\Controllers\Goods;

use App\Model\CartModel;
use App\Model\GoodsModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
       /**
        * 商品详情
        */
    public function goodsList($goods_id){
        //根据商品id查一条数据 goods_id
        $goods=GoodsModel::where(['goods_id'=>$goods_id])->first();
//        print_r($goods);
        if(!$goods){
            header('Refresh:2;url=/');
            echo '商品不存在,正在跳转至首页';
            exit;
        }
        return view('goods.index',['goods'=>$goods]);

    }


}