<?php

namespace yzh52521\ShoppingCart;

use think\Facade as BaseFacade;

class ShopCart extends BaseFacade
{
    protected static function getFacadeClass()
    {
        return Cart::class;
    }
}
