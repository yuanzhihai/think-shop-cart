<?php

namespace yzh52521\ShoppingCart;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use think\contract\Arrayable;
use think\contract\Jsonable;
use think\Collection as BaseCollection;

class Collection extends BaseCollection implements ArrayAccess, Countable, IteratorAggregate, JsonSerializable, Arrayable, Jsonable
{
    public function get($key, $default = null)
    {
        if ($this->offsetExists($key)) {
            return $this->items[$key];
        }

        return value($default);
    }

    public function put($key, $value)
    {
        $this->offsetSet($key, $value);

        return $this;
    }

    public function has($key)
    {
        return $this->offsetExists($key);
    }
}
