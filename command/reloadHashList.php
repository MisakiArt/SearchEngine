<?php
require_once dirname(__FILE__)."/../config.php";
require_once dirname(__FILE__)."/../model/spider.php";

$config = new config();
$spider = new spider();
$spider->setModifyDate();
$config = new config();
$redis_config = $config ->getRedisConfig(md5($config::CODERNAME.$spider->modify_date));
$redis = new redis();
$redis ->connect($redis_config['ip'],$redis_config['port']);
$redis ->del('MiProjectUrlHash');
$redis ->flushDB();
$date = $config->getReloadDate();
$hashList =file_get_contents(dirname(__FILE__)."/../historyHashList/hashListLog".$date.".txt");
if($hashList){
    $redis->hMset('MiProjectUrlHash',unserialize($hashList));
}
$url=$redis -> hVals('MiProjectUrlHash');

echo 'reloadlist number_cout:'.count($url);