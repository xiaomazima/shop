<?php

namespace App\Http\Controllers\Order;

use App\Model\CartModel;
use App\Model\GoodsModel;
use App\Model\UserModel;
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
            'is_pay'      => 1,
            'is_delete'=>1
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

        $where=[
          'is_delete'=>1 ,
          'u_id'=>session()->get('uid')
        ];
        $order_data=OrderModel::where($where)->get()->toArray();
        if(empty($order_data)){
            die('没有订单，请去选择商品');
        }

//        print_r($order_data);
        return view('order.orderlist',['order_data'=>$order_data]);
    }


    /**
     * 结算
     */
    public function orderPay($id){
        //根据id 查是否有此订单
        $order_res=OrderModel::where(['id'=>$id,'u_id'=> session()->get('uid')])->first();
//        print_r($order_res);exit;
        if(empty($order_res)){
            echo '此订单不存在';
            header("refresh:2;url='/orderList'");
        }else{
            $where=[
                'is_pay'=>2,
                'pay_amount'=> mt_rand(100,999),
                'pay_time'=>time()
            ];
            $data=OrderModel::where(['id'=>$id,'u_id'=> session()->get('uid')])->update($where);
//            var_dump($data);
            if(empty($data)){
                echo '支付失败';
            }else{
                echo '支付成功';
                //积分 累加
                $user_int=UserModel::where(['id'=>session()->get('uid')])->value('integral');
                $user_integral=$user_int+$where['pay_amount'];
                UserModel::where(['id'=>session()->get('uid')])->update(['integral'=>$user_integral]);
                header("refresh:2;url='/orderList'");
            }
        }

    }


    /**
     * 取消订单
     */
    public function orderDel($id){
        //根据 id和uid删除订单  2已删除
        $where=[
            'is_delete'=>2,

        ];
        $del=OrderModel::where(['id'=>$id,'u_id'=>session()->get('uid')])->update($where);
        if($del){
            echo '取消订单成功';
            header("refresh:2; url='/orderList'");
        }
    }
}