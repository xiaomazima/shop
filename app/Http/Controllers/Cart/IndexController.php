<?php

namespace App\Http\Controllers\Cart;

use App\Model\CartModel;
use App\Model\GoodsModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class IndexController extends Controller
{
    public $uid;
    public function __construct()
    {
        $this->middleware(function($request,$next){
            $this->uid=session()->get('uid');
            return $next($request);
        });
    }

    //
    public function index(Request $request)
    {
//        $cart_goods =session()->get('cart_goods');
////        print_r($cart_goods);
//        if(empty($cart_goods)){
//            exit('购物车没有数据');
//        }else{
//            foreach($cart_goods as $v){
//                $res=GoodsModel::where(['goods_id'=>$v])->first()->toArray();
//                print_r($res);
//            }
//        }
    //获取用户id
        $uid=session()->get('uid');
        //根据用户id查用户购物车所有的数据
        $data=CartModel::where(['uid'=>$uid])->get()->toArray();
//        print_r($data);exit;
        if(empty($data)){
            die("购物车是空的");
        }else{
            //获取商品最新信息
            foreach($data as $k=>$v){
                $goods_info = GoodsModel::where(['goods_id'=>$v['goods_id']])->first()->toArray();
                $goods_info['num']  = $v['num'];
                //echo '<pre>';print_r($goods_info);echo '</pre>';
                $list[] = $goods_info;
            }
            $data = [
                'list'  => $list
            ];
        }

        return view('cart.index',$data);
    }

    /**
     * 添加购物车数据
     */
    public function cartAdd(Request $request){
        //获取商品id和购买数量
        $goods_id=$request->input('goods_id');
        $num=$request->input('num');
//        print_r($num);exit();
        //检查商品表库存    根据商品id查看该商品库存
        $store_num=GoodsModel::where(['goods_id'=>$goods_id])->value('store');
        if($store_num<=0){
            $response=[
                'errno' => 5001,
                'msg'   => '库存不足'
            ];
            return $response;
        }
        //检查购物车重复商品
        $cart_goods = CartModel::where(['uid'=>$this->uid])->get()->toArray();
        if($cart_goods){
            $goods_id_arr = array_column($cart_goods,'goods_id');

            if(in_array($goods_id,$goods_id_arr)){
                $response = [
                    'errno' => 5002,
                    'msg'   => '商品已在购物车中，请勿重复添加'
                ];
                return $response;
            }
        }

        //写入购物车表
        $data = [
            'goods_id'  => $goods_id,
            'num'       => $num,
            'add_time'  => time(),
            'uid'       => $this->uid,
            'session_token' => session()->get('u_token')
        ];
//        print_r($data);exit;

        $cid=CartModel::insertGetId($data);

        if(!$cid){
            $response = [
                'errno' => 5002,
                'msg'   => '添加购物车失败，请重试'
            ];
            return $response;
        }


        $response = [
            'error' => 0,
            'msg'   => '添加成功'
        ];
        return $response;


    }


    /**
     * 删除购物车数据
     */

    public function cartDel($goods_id){
        $uid = session()->get('uid');
        $res= CartModel::where(['uid'=>$uid,'goods_id'=>$goods_id])->delete();
        if($res){
            echo '商品id：'.$goods_id.'删除成功';
            header("refresh:2;url='/cart'");
        }else{
            echo 'fail';
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
            $list = GoodsModel::paginate(2);
            return view('cart.list',['list'=>$list]);

        }
    /**
     * 搜索
     */
    public function goods_sou(Request $request){
        $u_name=$request->input('goods_name');
//        print_r($u_name);
        $goods_page=GoodsModel::where('goods_name','like',"%{$u_name}%")->paginate(2);
//        print_r($goods_page);exit;
        return view('cart/goods',['goods_page'=> $goods_page]);
    }

}
