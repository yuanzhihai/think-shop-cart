<?php

namespace yzh52521\ShoppingCart;


use yzh52521\ShoppingCart\command\Publish;
use yzh52521\ShoppingCart\storage\SessionStorage;

class Service extends \think\Service
{

    public function register()
    {
        $this->app->bind( 'cart',function () {
            $storage = config( 'cart.storage' );
            $cart    = new Cart( $this->app->make( $storage ) );
            if (SessionStorage::class == $storage) {
                return $cart;
            }
            $table = config( 'cart.table' );
            return new Cart( $this->app->make( $storage( $table ) ) );
        } );
    }

    public function boot()
    {
        $this->commands( ['shoppingcart:publish' => Publish::class] );
    }


}
