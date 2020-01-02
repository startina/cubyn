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
     * @throws \Exception|\ErrorException
     */
    public function get(int $offset = 0, int $limit = null, array $filters = [], array $sort = ['id' => 'DESC'])
    {
        $params = [];
        $params['filters'] = $filters;
        $params['sort'] = $sort;
        $params['offset'] = $offset;
        $params['limit'] = $limit;
        return $this->request('search/parcel', $params);
    }

    /**
     * 获取订单列表，包括IMEI/序列号数据
     * @param array $status
     * @param array $sort
     * @param int $offset
     * @param int|null $limit
     * @return bool|object|null
     * @throws \Exception|\ErrorException
     */
    public function getSearch(int $offset = 0, int $limit = null, array $status = ['CREATED','PICKED','SHIPPED','CARRIER_IN_TRANSIT','CARRIER_OUT_FOR_DELIVERY','CARRIER_EXCEPTION','CUBYN_EXCEPTION','CARRIER_FAILED_ATTEMPT','CARRIER_DELIVERED'])
    {
        $params = [];
        if ($status) {
            $params['status'] = implode(',', $status);
        }
        $params['offset'] = $offset;
        $params['limit'] = $limit;
        $params['sort'] = 'id';
        $params['order'] = 'DESC';
        $params['isFulfillment'] = 1;
        $params['arrayFormat'] = 'comma';
        $params['type'] = 'SHIPMENT,DUPLICATE';
        $params['st'] = $this->getToken();
        return $this->request('search/parcel', $params);
    }

    /**
     * @desc 创建包裹 - 发货包裹
     * @url https://developers-storage.cubyn.com/#step-3-create-parcels
     * @param $firstName
     * @param $lastName
     * @param $address
     * @param $orderRef
     * @param $objectCount
     * @param $items
     * @return bool|object|null
     * @throws \Exception|\ErrorException
     */
    public function createParcels($firstName, $lastName, $address, $orderRef, $objectCount, $items)
    {
        $params = [];
        $params['firstName'] = $firstName;
        $params['lastName'] = $lastName;
        $params['address'] = $address;
        $params['orderRef'] = $orderRef;
        $params['objectCount'] = $objectCount;
        $params['items'] = $items;
        return $this->request('parcels', [], 'post');
    }
}