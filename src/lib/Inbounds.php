<?php
namespace startina\cubyn\lib;
use startina\cubyn\Basic;

class Inbounds extends Basic {
    /**
     * @desc 获取发货单信息
     * @url https://shipper.cubyn.com/inbounds
     * @param $filters array 过滤项
     * @param $sort array 排序字段
     * @param $offset int 起始位置
     * @param $limit int 每页数量
     * @return bool|object|null
     * @throws \Exception|\ErrorException
     */
    public function get(int $offset = 0, int $limit = null, array $filters = [], array $includes = ['packingUnitsQuantity'], array $sort = ['createdAt' => 'DESC'])
    {
        $params = [];
        $params['filters'] = $filters;
        $params['includes'] = $includes;
        $params['sort'] = $sort;
        $params['offset'] = $offset;
        $params['limit'] = $limit;
        return $this->request('storage-inbound/orders/', $params);
    }
    public function getBarCodes($pid) {
        $uri = "storage-inbound/orders/{$pid}/barcodes";
        return $this->request($uri);
    }
    /**
     * @desc 发送库存
     * @url https://developers-storage.cubyn.com/#step-1-send-inventory
     * @param $packingUnits
     * @param $items
     * @return bool|object|null
     * @throws \Exception|\ErrorException
     */
    public function send($packingUnits, $items)
    {
        return $this->request('storage-inbound/orders', ['packingUnits' => $packingUnits, 'items' => $items], 'post');
    }
}