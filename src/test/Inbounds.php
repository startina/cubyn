<?php
namespace startina\cubyn\test;

class Inbounds extends Basic {
    /**
     * @desc 获取发货单信息
     * @url https://shipper.cubyn.com/inbounds
     * @param $filters array 过滤项
     * @param $sort array 排序字段
     * @param $offset int 起始位置
     * @param $limit int 每页数量
     * @return bool|object|null
     * @throws \ErrorException
     */
    public function get(array $filters = [], array $includes = ['packingUnitsQuantity'], array $sort = ['createdAt' => 'DESC'], int $offset = 0, int $limit = null)
    {
        return $this->client->inbounds()->get($filters, $includes, $sort, $offset, $limit);
    }
}