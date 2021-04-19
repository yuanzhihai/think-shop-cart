<?php
/**
 * Created by PHP@大海 [三十年河东三十年河西,莫欺少年穷.!]
 * User: yuanzhihai
 * Date: 2021/4/16
 * Time: 10:48 上午
 * Author: PHP@大海 <396751927@qq.com>
 *       江城子 . 程序员之歌
 *
 *  十年生死两茫茫，写程序，到天亮。
 *      千行代码，Bug何处藏。
 *  纵使上线又怎样，朝令改，夕断肠。
 *
 *  领导每天新想法，天天改，日日忙。
 *     相顾无言，惟有泪千行。
 *  每晚灯火阑珊处，夜难寐，加班狂。
 */

namespace yzh52521\ShoppingCart\storage;


use think\facade\Db;
use think\helper\Arr;
use yzh52521\ShoppingCart\Collection;
use yzh52521\ShoppingCart\Item;

class DatabaseStorage implements Storage
{
    /**
     * @var string
     */
    private $table = 'shopping_cart';

    /**
     * @var array
     */
    private $filed = ['__raw_id', 'id', 'name', 'qty', 'price', 'total', '__model', 'type', 'status'];

    /**
     * @param $key
     * @param $values
     */
    public function set($key, $values)
    {
        if (is_null($values)) {
            $this->forget($key);

            return;
        }

        $rawIds = $values->column('__raw_id');

        //Delete the data that has been removed from cart.
        Db::name($this->table)->whereNotIn('__raw_id', $rawIds)->where('key', $key)->delete();

        $keys = explode('.', $key);

        $userId = end($keys);
        $guard = prev($keys);
        $values=$values->toArray();
        foreach ($values as $value) {
            $item = Arr::only($value, $this->filed);
            $attr = json_encode(Arr::except($value, $this->filed));
            $insert = array_merge($item, ['attributes' => $attr, 'key' => $key, 'guard' => $guard, 'user_id' => $userId]);
            if (DB::name($this->table)->where(['key' => $key, '__raw_id' => $item['__raw_id']])->find()) {
                DB::name($this->table)->where(['key' => $key, '__raw_id' => $item['__raw_id']])
                    ->update(Arr::except($insert, ['key', '__raw_id']));
            } else {
                DB::name($this->table)->insert($insert);
            }
        }
    }

    /**
     * @param $key
     * @param null $default
     *
     * @return Collection
     */
    public function get($key, $default = null)
    {
        $items = DB::name($this->table)->where('key', $key)->select();
        $collection = [];
        foreach ($items as $item) {
            $item = json_decode(json_encode($item), true);
            $attr = json_decode($item['attributes'], true);
            $item = Arr::only($item, $this->filed);
            $item = array_merge($item, $attr);
            $collection[$item['__raw_id']] = new Item($item);
        }
        return new Collection($collection);
    }

    /**
     * @param $key
     */
    public function forget($key)
    {
        DB::name($this->table)->where('key', $key)->delete();
    }
}
