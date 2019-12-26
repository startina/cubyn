<?php
namespace startina\cubyn\lib;
use startina\cubyn\Basic;

class Product extends Basic {
    /**
     * @desc 产品目录
     * @url https://developers-storage.cubyn.com/#step-2-track-inventory
     * @param $query string SKU名称模糊搜索
     * @param $sort array 排序字段
     * @param $offset int 起始位置
     * @param $limit int 每页数量
     * @return bool|object|null
     * @throws \ErrorException
     */
    public function get(string $query = '', array $sort = ['sku' => 'ASC'], $offset = 0, $limit = 50)
    {
        return $this->request('product-catalog/products/');
    }

}