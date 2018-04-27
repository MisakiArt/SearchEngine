<?php
require_once "model/phpQuery.php";
require_once "model/spider.php";
require 'vendor/autoload.php';

$hosts = array('127.0.0.1:9200');
$client = Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
$bulk=['index'=>'search_engine_ani','type'=>'search_record'];
$redis = new redis();
$redis ->connect('127.0.0.1','6379');
$referer='http://www.dilidili.wang';
while(true){
    $params=$redis->brPop('MiProjectUrlList',100);
    spider::getInformationByUrl($params[1],$referer,$bulk['body']);
    if(count($bulk['body'])>20){
       $res=$client->bulk($bulk);
       print_r($res);
        $bulk=[];
        $bulk=['index'=>'search_engine_ani','type'=>'search_record'];
    }
}