<?php

namespace yzh52521\ShoppingCart;

use yzh52521\ShoppingCart\storage\SessionStorage;
use yzh52521\ShoppingCart\storage\Storage;


class Cart
{
    /**
     * Session manager.
     *
     * @var \yzh52521\Shoppingcart\storage\Storage
     */
    protected $storage;

    protected $name = 'shopping_cart.default';

    /**
     * Associated model name.
     *
     * @var string
     */
    protected $model;

    public function __construct(SessionStorage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param Storage $storage
     */
    public function setStorage(Storage $storage)
    {
        $this->storage = $storage;
    }


    public function name($name)
    {
        $this->name = 'shopping_cart.'.$name;

        return $this;
    }

    /**
     * Associated model.
     *
     * @param  string $model The name of the model
     * @return Cart
     * @throws Exception
     */
    public function associate($model)
    {
        if (!class_exists($model)) {
            throw new Exception("Invalid model name '$model'.");
        }
        $this->model = $model;

        return $this;
    }

    public function all()
    {
        return $this->getCart();
    }

    public function add($id, $name = null, $qty = null, $price = null, array $attributes = [])
    {
        return $this->addRow($id, $name, $qty, $price, $attributes);
    }

    public function update($rawId, $attribute)
    {
        if (!$row = $this->get($rawId)) {
            throw new Exception('Item not found.');
        }

        if (is_array($attribute)) {
            $raw = $this->updateAttribute($rawId, $attribute);
        } else {
            $raw = $this->updateQty($rawId, $attribute);
        }

        return $raw;
    }

    public function remove($rawId)
    {
        if (!$row = $this->get($rawId)) {
            return true;
        }

        $cart = $this->getCart();

        $cart->forget($rawId);

        $this->save($cart);

        return true;
    }

    public function get($rawId)
    {
        $row = $this->getCart()->get($rawId);

        return null === $row ? null : new Item($row);
    }

    public function destroy()
    {
        $this->save(null);

        return true;
    }

    public function clean()
    {
        $this->destroy();
    }

    public function total()
    {
        return $this->totalPrice();
    }

    public function totalPrice()
    {
        $total = 0;

        $cart = $this->getCart();

        if ($cart->isEmpty()) {
            return $total;
        }

        foreach ($cart as $row) {
            $total += $row->qty * $row->price;
        }

        return $total;
    }

    public function count($totalItems = true)
    {
        $items = $this->getCart();

        if (!$totalItems) {
            return $items->count();
        }

        $count = 0;

        foreach ($items as $row) {
            $count += $row->qty;
        }

        return $count;
    }

    public function countRows()
    {
        return $this->count(false);
    }

    public function search(array $search)
    {
        $rows = new Collection();

        if (empty($search)) {
            return $rows;
        }

        foreach ($this->getCart() as $item) {
            if (array_intersect_assoc($item->intersect($search)->toArray(), $search)) {
                $rows->put($item->__raw_id, $item);
            }
        }

        return $rows;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function isEmpty()
    {
        return $this->count() <= 0;
    }

    protected function addRow($id, $name, $qty, $price, array $attributes = [])
    {
        if (!is_numeric($qty) || $qty < 1) {
            throw new Exception('Invalid quantity.');
        }

        if (!is_numeric($price) || $price < 0) {
            throw new Exception('Invalid price.');
        }

        $cart = $this->getCart();

        $rawId = $this->generateRawId($id, $attributes);

        if ($row = $cart->get($rawId)) {
            $row = $this->updateQty($rawId, $row->qty + $qty);
        } else {
            $row = $this->insertRow($rawId, $id, $name, $qty, $price, $attributes);
        }

        return $row;
    }

    protected function generateRawId($id, $attributes)
    {
        ksort($attributes);

        return md5($id.serialize($attributes));
    }

    protected function save($cart)
    {
        $this->storage->set($this->name, $cart);
        return $cart;
    }


    protected function getCart()
    {
        $cart = $this->storage->get($this->name);

        return $cart instanceof Collection ? $cart : new Collection();
    }

    protected function updateRow($rawId, array $attributes)
    {
        $cart = $this->getCart();

        $row = $cart->get($rawId);

        foreach ($attributes as $key => $value) {
            $row->put($key, $value);
        }

        if (count(array_intersect(array_keys($attributes), ['qty', 'price']))) {
            $row->put('total', $row->qty * $row->price);
        }

        $cart->put($rawId, $row);

        $this->save($cart);

        return $row;
    }

    protected function insertRow($rawId, $id, $name, $qty, $price, $attributes = [])
    {
        $newRow = $this->makeRow($rawId, $id, $name, $qty, $price, $attributes);

        $cart = $this->getCart();

        $cart->put($rawId, $newRow);

        $this->save($cart);

        return $newRow;
    }

    protected function makeRow($rawId, $id, $name, $qty, $price, array $attributes = [])
    {
        return new Item(array_merge([
            '__raw_id' => $rawId,
            'id' => $id,
            'name' => $name,
            'qty' => $qty,
            'price' => $price,
            'total' => $qty * $price,
            '__model' => $this->model,
        ], $attributes));
    }

    protected function updateQty($rawId, $qty)
    {
        if ($qty <= 0) {
            return $this->remove($rawId);
        }

        return $this->updateRow($rawId, ['qty' => $qty]);
    }

    protected function updateAttribute($rawId, $attributes)
    {
        return $this->updateRow($rawId, $attributes);
    }
}
