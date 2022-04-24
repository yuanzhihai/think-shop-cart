<?php

namespace yzh52521\ShoppingCart;


use yzh52521\ShoppingCart\command\Publish;
use yzh52521\ShoppingCart\storage\SessionStorage;

class Service extends \think\Service
{

    public function register()
    {
        $this->app->bind('cart', function () {
            $storage = config('cart.storage');
            $cart    = new Cart(new $storage());
            if (SessionStorage::class == $storage) {
                return $cart;
            }
            $table = config('cart.table');
            return new Cart(new $storage($table));
        });
    }

    public function boot()
    {
        $this->commands(['shoppingcart:publish' => Publish::class]);
    }


}
