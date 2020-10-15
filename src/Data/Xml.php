<?php
/**
 * Created by PhpStorm.
 * User: chengxun
 * Date: 2017/2/21
 * Time: 13:56
 */

namespace Data;


class Xml
{
    // 输出参数
    public $options = [
        // 根节点名
        'root_node' => 'plat12312res',
        // 根节点属性
        'root_attr' => '',
        //数字索引的子节点名
        'item_node' => 'item',
        // 数字索引子节点key转换的属性名
        'item_key'  => 'id',
        // 数据编码
        'encoding'  => 'utf-8',
    ];

    protected $contentType = 'text/xml';

    public function __construct($config = [])
    {
        $this->options = array_merge($this->options,$config);
    }
    /**
     * 解析XML格式的字符串
     * @param $str
     * @param int $is_array
     * @return array|mixed 是否输出数组 1 输出 2输出json
     */
    public function xml_parser($str, $is_array=1){
        $xml_parser = xml_parser_create();
        $result = [];
        if(!xml_parse($xml_parser,$str,true)){
            xml_parser_free($xml_parser);
        }else {
            $result = json_encode(simplexml_load_string($str));
        }
        if($is_array == 1){
            return json_decode($result, true);
        }else{
            return $result;
        }
    }

    /**
     * 处理数据
     * @access protected
     * @param mixed $data 要处理的数据
     * @return mixed
     */
    public function encode($data)
    {
        // XML数据转换
        return $this->xmlEncode($data, $this->options['root_node'], $this->options['item_node'], $this->options['root_attr'], $this->options['item_key'], $this->options['encoding']);
    }

    /**
     * XML编码
     * @param mixed $data 数据
     * @param string $root 根节点名
     * @param string $item 数字索引的子节点名
     * @param string $attr 根节点属性
     * @param string $id   数字索引子节点key转换的属性名
     * @param string $encoding 数据编码
     * @return string
     */
    protected function xmlEncode($data, $root, $item, $attr, $id, $encoding)
    {
        if (is_array($attr)) {
            $array = [];
            foreach ($attr as $key => $value) {
                $array[] = "{$key}=\"{$value}\"";
            }
            $attr = implode(' ', $array);
        }
        $attr = trim($attr);
        $attr = empty($attr) ? '' : " {$attr}";
        $xml  = "<?xml version=\"1.0\" encoding=\"{$encoding}\"?>";
        $xml .= "<{$root}{$attr}>";
        $xml .= $this->dataToXml($data, $item, $id);
        $xml .= "</{$root}>";
        return $xml;
    }

    /**
     * 数据XML编码
     * @param mixed  $data 数据
     * @param string $item 数字索引时的节点名称
     * @param string $id   数字索引key转换为的属性名
     * @return string
     */
    protected function dataToXml($data, $item, $id)
    {
        $xml = $attr = '';
        foreach ($data as $key => $val) {
            if (is_numeric($key)) {
                $id && $attr = " {$id}=\"{$key}\"";
                $key         = $item;
            }
            $xml .= "<{$key}{$attr}>";
            $xml .= (is_array($val) || is_object($val)) ? $this->dataToXml($val, $item, $id) : $val;
            $xml .= "</{$key}>";
        }
        return $xml;
    }
}