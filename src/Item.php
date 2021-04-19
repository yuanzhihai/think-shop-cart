<?php

namespace yzh52521\ShoppingCart;


class Item extends Collection
{
    protected $model;

    public function __get($property)
    {
        if ($this->has($property)) {
            return $this->get($property);
        }

        if (!$this->get('__model')) {
            return;
        }

        $model = $this->get('__model');

        $class = explode('\\', $model);

        if (strtolower(end($class)) === $property || 'model' === $property) {
            $model = new $model();
            return $model->find($this->id);
        }
    }

    public function rawId()
    {
        return $this->__raw_id;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->rawId();
    }
}
