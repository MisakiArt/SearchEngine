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

$HashList = $redis->hGetAll('MiProjectUrlHash');
$date = date('Ymd');
$myfile = fopen(dirname(__FILE__)."/../historyHashList/hashListLog".$date.".txt", "w") or die("Unable to open file!");
$txt = serialize($HashList);
fwrite($myfile, $txt);
fclose($myfile);