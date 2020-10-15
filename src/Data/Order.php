<?php

namespace Potato\Data;
/**
 * Created by PhpStorm.
 * User: tossboy
 * Date: 2017/3/24
 * Time: 13:09
 */

class Order
{
    
    /**
     * 生成随机字符串格式的订单
     * @param int $randLength   长度
     * @param bool $addTime     是否加入当前时间戳
     * @param bool $includeNumber   是否包含数字
     * @return string
     */
    public static function getRandStr ($randLength = 6, $addTime = true, $includeNumber = false)
    {
        if ($includeNumber){
            
            $chars = 'abcdefghijklmnopqrstuvwxyz123456789';
            
        } else{
            
            $chars = '0123456789';
            
        }
        $len = strlen ($chars);
        
        $randStr = '';
        
        for ($i = 0; $i < $randLength; $i++) {
            
            $randStr .= $chars[rand (0, $len - 1)];
            
        }
        
        $tokenvalue = $randStr;
        
        if ($addTime){
            
            $tokenvalue = $randStr . time ();
            
        }
        
        return $tokenvalue;
    }

    /**
     * 生成日期格式的订单号
     * @return string
     */
    public static function timeOrder ()
    {

        //订单号码主体（YYYYMMDDHHIISSNNNNNNNN）
        $order_id_main = date ('YmdHis') . rand (10000000, 99999999);

        //订单号码主体长度
        $order_id_len = strlen ($order_id_main);

        $order_id_sum = 0;

        for ($i = 0; $i < $order_id_len; $i++) {

            $order_id_sum += (int)(substr ($order_id_main, $i, 1));

        }

        //唯一订单号码（YYYYMMDDHHIISSNNNNNNNNCC）
        $order_num = $order_id_main . str_pad ((100 - $order_id_sum % 100) % 100, 2, '0', STR_PAD_LEFT);
        
        return $order_num;
    }

}