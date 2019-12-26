<?php
namespace startina\cubyn\test;

class Product extends Basic {

    /**
     * @param $query string SKU名称模糊搜索
     * @param $sort array 排序字段
     * @param $offset int 起始位置
     * @param $limit int 每页数量
     * @return bool|object|null
     * @throws \ErrorException
     */
    public function get(string $query = '', array $sort = ['sku' => 'ASC'], int $offset = 0, int $limit = null)
    {
        return $this->client->product()->get($query, $sort, $offset, $limit);
    }
}