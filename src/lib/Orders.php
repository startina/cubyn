<?php
namespace startina\cubyn\lib;
use startina\cubyn\Basic;

class Orders extends Basic {
    /**
     * @desc 订单信息
     * @url https://shipper.cubyn.com/orders
     * @param $filters array 过滤条件
     * @param $sort array 排序字段
     * @param $offset int 起始位置
     * @param $limit int 每页数量
     * @return bool|object|null
     * @throws \ErrorException
     */
    public function get(array $filters = [], array $sort = ['id' => 'DESC'], int $offset = 0, int $limit = null)
    {
        $params = [];
        $params['filters'] = $filters;
        $params['sort'] = $sort;
        $params['offset'] = $offset;
        $params['limit'] = $limit;
        return $this->request('parcels', $params);
    }

}