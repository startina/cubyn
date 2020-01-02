<?php
namespace startina\cubyn;
use startina\cubyn\lib\Inbounds;
use startina\cubyn\lib\Inventory;
use startina\cubyn\lib\Orders;

/**
 * @method Inbounds inbounds()
 * @method Inventory inventory()
 * @method Orders orders()
 * Class Client
 * @package startina\cubyn
 */
class Client extends Basic {
    protected $ins = [];

    public function __call($name, $arguments)
    {
        $nameSpace = __NAMESPACE__.'\lib';
        $class = "{$nameSpace}\\".ucfirst($name);
        if (!class_exists($class)) {
            $this->setError('方法不存在');
            return false;
        }
        if (!isset($this->ins[$name])) {
            $this->ins[$name] = new $class($this->key, $this->connectTimeout, $this->errorTimesLimit);
        }
        return $this->ins[$name];
    }



}