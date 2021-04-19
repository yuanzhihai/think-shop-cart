<?php

namespace yzh52521\ShoppingCart;


use yzh52521\ShoppingCart\command\CreateShoppingCart;

class Service extends \think\Service
{

    public function boot()
    {
        $this->commands([
            CreateShoppingCart::class,
        ]);
    }
}
