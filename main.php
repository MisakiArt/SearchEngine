<?php
require_once "model/phpQuery.php";
require_once "model/spider.php";
require 'vendor/autoload.php';
ignore_user_abort();
set_time_limit(0);

$redis = new redis();
$redis ->connect('127.0.0.1','6379');
$redis -> flushAll();
$spider=new spider();
$referer='http://www.dilidili.wang';
$spider->getUrl(['http://www.dilidili.wang/'],'/anime/',$referer);
//$url=$redis -> hVals('MiProjectUrlHash');
//print_r($url);
//if(!$redis->hExists('MiProjectUrlHash', json_encode('http://www.dilidili.wang/anime/2018/'))){
//    echo 1;
//}
//$match='/http:\/\/www.dilidili.wang\/anime/';
//foreach ($url as $u){
//    if(!preg_match($match,$u))
//        $u='http://www.dilidili.wang'.$u;
//    $message=spider::getTitleByUrl($url,'',$referer);
//
//}
// 
//Connection was reset 目标网站服务器不稳定。
//网页为空，ajax方式请求网站，去network做同样的请求





















