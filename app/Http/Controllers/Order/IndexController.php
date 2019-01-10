<?php

namespace App\Http\Controllers\Order;

use App\Model\CartModel;
use App\Model\GoodsModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\OrderModel;

class IndexController extends Controller
{
    //

    public function index()
    {
        echo __METHOD__;
    }
    /**
     * 下单
     */
    public function orderAdd(){
        //查询购物车中的数据
        $cart_goods=CartModel::where(['uid'=>session()->get('uid')])->orderBy('id','desc')->get()->toArray();
//        print_r($cart_goods);
        if(empty($cart_goods)){
           die('购物车中没有数据');
       }
        //总价格
        $order_amount = 0;
        foreach($cart_goods as $k=>$v){
            $goods_info = GoodsModel::where(['goods_id'=>$v['goods_id']])->first()->toArray();
            $goods_info['num'] = $v['num'];
            $list[] = $goods_info;

            //计算订单价格 = 商品数量 * 单价
            $order_amount += $goods_info['price'] * $v['num'];
        }
        //生成订单号
        $order_sn=OrderModel::generateOrderSN();
       echo  $order_sn;
        $data = [
            'order_sn'      => $order_sn,
            'u_id'           => session()->get('uid'),
            'add_time'      => time(),
            'order_amount'  => $order_amount,
            'status'      => 1
        ];

        $oid = OrderModel::insertGetId($data);
        if(!$oid){
            echo '生成订单失败';
        }else{
            echo '下单成功,订单号：'.$oid .' 跳转支付';
            header("refresh:2; url='/orderList'");
        }



        //清空购物车
        CartModel::where(['uid'=>session()->get('uid')])->delete();

    }

    /**
     * 订单展示
     */
    public function orderList(){
        $order_data=OrderModel::where(['u_id'=>session()->get('uid')])->get()->toArray();
        if(empty($order_data)){
            die('没有订单，请去选择商品');
        }

//        print_r($order_data);
        return view('order.orderlist',['order_data'=>$order_data]);
    }

}