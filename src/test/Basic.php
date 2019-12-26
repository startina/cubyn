<?php
namespace startina\cubyn\test;
use startina\cubyn\Client;

abstract class Basic {
    protected $client = null;
    public function __construct($key)
    {
        $this->client = new Client($key);
    }
}