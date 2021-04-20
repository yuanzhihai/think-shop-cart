<?php

namespace yzh52521\ShoppingCart;


use yzh52521\ShoppingCart\command\Publish;

class Service extends \think\Service
{

    public function boot()
    {
        $this->commands(['shoppingcart:publish' => Publish::class]);
    }
}
