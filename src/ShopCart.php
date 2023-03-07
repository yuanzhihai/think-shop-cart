<?php

namespace yzh52521\ShoppingCart;


use think\Facade as BaseFacade;

/**
 * @see Cart
 * @mixin Cart
 * @method static setStorage( $storage ) 设置storage
 * @method static name( $name ) 设置当前购物车名称
 * @method static associate( $model ) 指定关联的商品模型
 * @method static all() 获取所有商品.
 * @method static add( $id,$name = null,$qty = null,$price = null,array $attributes = [] ) 添加到购物车
 * @method static update( $rawId,$attribute ) 更新购物车
 * @method static remove( $rawId ) 删除购物车
 * @method static get( $rawId ) 获取一个商品
 * @method static destroy() 清空购物车
 * @method static clean() 清空购物车
 * @method static total() 购物车总价格
 * @method static totalPrice() 购物车总价格
 * @method static count() 购物车数量
 * @method static countRows() 购物车行数
 * @method static search( $search ) 搜索商品
 * @method static getName()  获取当前购物车名称
 * @method static getModel() 获取购物车关联商品模型
 * @method static isEmpty()  检查购物车是否为空
 *
 */
class ShopCart extends BaseFacade
{

    protected static function getFacadeClass()
    {
        return 'cart';
    }

}
