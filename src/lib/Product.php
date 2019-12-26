<?php
namespace startina\cubyn\lib;
use startina\cubyn\Basic;

class Inventory extends Basic {

    /**
     * @desc 发送库存
     * @url https://developers-storage.cubyn.com/#step-1-send-inventory
     * @param $packingUnits
     * @param $items
     * @return bool|object|null
     * @throws \ErrorException
     */
    public function send($packingUnits, $items)
    {
        return $this->request('storage-inbound/orders', ['packingUnits' => $packingUnits, 'items' => $items], 'post');
    }
    public function get() {

    }

}