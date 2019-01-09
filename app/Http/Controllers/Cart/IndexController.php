<?php

namespace App\Http\Controllers\Cart;

use App\Model\CartModel;
use App\Model\GoodsModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{

    public function __construct()
    {

    }

    //
    public function index(Request $request)
    {
        $cart_goods =session()->get('cart_goods');
//        print_r($cart_goods);
        if(empty($cart_goods)){
            exit('购物车没有数据');
        }else{
            foreach($cart_goods as $v){
                $res=GoodsModel::where(['goods_id'=>$v])->first()->toArray();
                print_r($res);
            }
        }
    }


    /**
     * 添加商品
     */
    public function add($goods_id)
    {
        $cart_goods=session()->get('cart_goods');

        //是否在购物车里面 in_array 一个数组是否在另一个数组内
        if(!empty($cart_goods)){
            if(in_array($goods_id,$cart_goods)){
                echo '商品已存在';
                exit;
            }
        }

        session()->push('cart_goods',$goods_id);

        //减库存

        $where=[
            'goods_id'=>$goods_id
        ];

        $store=GoodsModel::where($where)->value('store');

        if($store<=0){
            exit('此商品没有库存');
        }
        $res=GoodsModel::where($where)->decrement('store');

        if($res){
            echo '添加成功';
        }

    }

    /**
     * 删除商品
     */
    public function del($goods_id)
    {
        $cart_goods=session()->get('cart_goods');

        if(in_array($goods_id,$cart_goods)){
            //执行删除
            foreach($cart_goods as $k=>$v) {
            if($goods_id==$v){
                session()->pull('cart_goods.'.$k);
            }
            }
            echo '删除成功';
        }else{
            exit('该商品不在购物车内');
        }

    }

    /**
     * 展示
     */
        public function clist(){
            $arr=GoodsModel::get()->toArray();
            return view('cart.list',['arr'=>$arr]);

        }

}
