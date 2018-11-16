<?php

/**
 * 接口基类
 */
class KApi extends CController
{
    /*
     * --------------------------------
     * start ： 统计接口调用次数 + 耗时
     * --------------------------------
     */
    protected $_start_time;
    protected $apiCnt = "/api/v1/ApiCallCnt";
    protected $apiSec = "/api/v1/ApiCallSec";

    public function __construct()
    {
        parent::__construct();
//        CLog::WriteLog("start this->route : " . var_export($this->getUrl(''), 1), "use_seconds");
        $this->_start_time = microtime(true);
        $key = $this->getUrl('') . "_cnt";
        if ($this->getRoute() != $this->apiCnt) {
            $this->redis->zIncrBy($key, 1, $this->getRoute());
        }
    }

    public function __destruct()
    {
        $key = $this->getUrl('') . "_sec";
        $use_seconds = microtime(true) - $this->_start_time;
        if ($this->getRoute() != $this->apiSec) {
            $this->redis->zAdd($key, $use_seconds, $this->getRoute());
        }
    }

    public function actionApiCallCnt()
    {
//        var_dump($this->getUrl(''));
        $key = $this->getUrl('') . "_cnt";
//        var_dump($key);

        $ret = $this->redis->zRevRange($key, 0, -1, true);
        $this->dump($ret);
        die;

    }

    public function actionApiCallSec()
    {
//        var_dump($this->getUrl(''));
        $key = $this->getUrl('') . "_sec";
//        var_dump($key);

        $ret = $this->redis->zRevRange($key, 0, -1, true);
        $this->dump($ret);
        die;

    }

    /*
     * --------------------------------
     * end ： 统计接口调用次数 + 耗时
     * --------------------------------
     */

    public function actionTest()
    {
        echo 1;
        die;
    }
}
