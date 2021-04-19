
购物车在电商场景中基本是必须的一个模块，本包是基于 [overtrue/laravel-shopping-cart][1] 进行扩展开发，主要实现了以下扩展：

1. 购物车数据支持 Database 存储
2. Item 增加 Model 属性返回。因为购物车可能是SPU或者SKU，因此直接通过 model 属性直接返回相关对象。
3. 支持多 Guard. 商城购物车和导购购物车。

> 已经完成了 Session 和 Database 模式下的单元测试 可放心使用.

## Installation

```
composer require yzh52521/think-shop-cart:~0.5 -vvv
```

### 会话初始化

`app\middleware.php`

```php

return [
    // Session初始化
    \think\middleware\SessionInit::class,
];
```

## Usage

### Select Storage

You can change data Storage in `config/cart.php` file.

```php
'storage' => \yzh52521\ShoppingCart\storage\DatabaseStorage::class,
  
'storage' => \yzh52521\ShoppingCart\storage\SessionStorage::class,
```

If you use Database Storage, you need to execute `php think cart:table`

### Add item to cart

Add a new item.

```php
Item | null Cart::add(
                    string | int $id,
                    string $name,
                    int $quantity,
                    int | float $price
                    [, array $attributes = []]
                 );
```

**example:**

```php
$row = Cart::add(37, 'Item name', 5, 100.00, ['color' => 'red', 'size' => 'M']);
// Item:
//    id       => 37
//    name     => 'Item name'
//    qty      => 5
//    price    => 100.00
//    color    => 'red'
//    size     => 'M'
//    total    => 500.00
//    __raw_id => '8a48aa7c8e5202841ddaf767bb4d10da'
$rawId = $row->rawId();// get __raw_id
$row->qty; // 5
...
```

### Update item

Update the specified item.

```php
Item Cart::update(string $rawId, int $quantity);
Item Cart::update(string $rawId, array $arrtibutes);
```

**example:**

```php
Cart::update('8a48aa7c8e5202841ddaf767bb4d10da', ['name' => 'New item name');
// or only update quantity
Cart::update('8a48aa7c8e5202841ddaf767bb4d10da', 5);
```

### Get all items

Get all the items.

```php
Collection Cart::all();
```

**example:**

```php
$items = Cart::all();
```


### Get item

Get the specified item.

```php
Item Cart::get(string $rawId);
```

**example:**

```php
$item = Cart::get('8a48aa7c8e5202841ddaf767bb4d10da');
```

### Remove item

Remove the specified item by raw ID.

```php
boolean Cart::remove(string $rawId);
```

**example:**

```php
Cart::remove('8a48aa7c8e5202841ddaf767bb4d10da');
```

### Destroy cart

Clean Shopping Cart.

```php
boolean Cart::destroy();
boolean Cart::clean(); // alias of destroy();
```

**example:**

```php
Cart::destroy();// or Cart::clean();
```

### Total price

Returns the total of all items.

```php
int | float Cart::total(); // alias of totalPrice();
int | float Cart::totalPrice();
```

**example:**

```php
$total = Cart::total();
// or
$total = Cart::totalPrice();
```


### Count rows

Return the number of rows.

```php
int Cart::countRows();
```

**example:**

```php
Cart::add(37, 'Item name', 5, 100.00, ['color' => 'red', 'size' => 'M']);
Cart::add(37, 'Item name', 1, 100.00, ['color' => 'red', 'size' => 'M']);
Cart::add(37, 'Item name', 5, 100.00, ['color' => 'red', 'size' => 'M']);
Cart::add(127, 'foobar', 15, 100.00, ['color' => 'green', 'size' => 'S']);
$rows = Cart::countRows(); // 2
```


### Count quantity

Returns the quantity of all items

```php
int Cart::count($totalItems = true);
```

`$totalItems` : When `false`,will return the number of rows.

**example:**

```php
Cart::add(37, 'Item name', 5, 100.00, ['color' => 'red', 'size' => 'M']);
Cart::add(37, 'Item name', 1, 100.00, ['color' => 'red', 'size' => 'M']);
Cart::add(37, 'Item name', 5, 100.00, ['color' => 'red', 'size' => 'M']);
$count = Cart::count(); // 11 (5+1+5)
```

### Search items

Search items by property.

```php
Collection Cart::search(array $conditions);
```

**example:**

```php
$items = Cart::search(['color' => 'red']);
$items = Cart::search(['name' => 'Item name']);
$items = Cart::search(['qty' => 10]);
```

### Check empty

```php
bool Cart::isEmpty();
```

### Specifies the associated model

Specifies the associated model of item.

```php
Cart Cart::associate(string $modelName);
```

**example:**

```php
Cart::associate('app\model\Goods');
$item = Cart::get('8a48aa7c8e5202841ddaf767bb4d10da');
$item->goods->name; // $item->goods is instanceof 'app\model\Goods'
```


# The Collection And Item

`Collection` and `Overtrue\LaravelShoppingCart\Item` are instanceof `Illuminate\Support\Collection`, Usage Refer to：[Collections - Laravel doc.](http://laravel.com/docs/5.0/collections)

properties of `Overtrue\LaravelShoppingCart\Item`:

- `id`       - your goods item ID.
- `name`     - Name of item.
- `qty`      - Quantity of item.
- `price`    - Unit price of item.
- `total`    - Total price of item.
- `__raw_id` - Unique ID of row.
- `__model`  - Name of item associated Model.
- ... custom attributes.

And methods:

 - `rawId()` - Return the raw ID of item.


  [1]: https://github.com/overtrue/laravel-shopping-cart
