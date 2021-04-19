<?php
/**
 * Created by PHP@大海 [三十年河东三十年河西,莫欺少年穷.!]
 * User: yuanzhihai
 * Date: 2021/4/16
 * Time: 10:36 上午
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


use think\facade\Session;

class SessionStorage implements Storage
{
    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        Session::set($key,$value);
    }

    /**
     * @param $key
     * @param null $default
     */
    public function get($key, $default = null)
    {
        return Session::get($key,$default);
    }

    /**
     * @param $key
     */
    public function forget($key)
    {
        Session::delete($key);
    }
}
