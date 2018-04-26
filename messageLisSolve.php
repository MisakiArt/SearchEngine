<?php
require_once "model/phpQuery.php";
require_once "model/spider.php";
require 'vendor/autoload.php';

$redis = new redis();
$redis ->connect('127.0.0.1','6379');
$hosts = array('127.0.0.1:9200');
$client = Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
$params = [
   'index'=>'search_engine_ani',
   "mappings"=>[
       "area"
   ]
];
$resp = $client->index($params);
$referer='http://www.dilidili.wang';
while (true){
//    $url=$redis -> hVals('MiProjectUrlList');哈希表里的数据全部取出
    handle($url=$redis->brPop('MiProjectUrlList'));
    $match='/http:\/\/www.dilidili.wang\/anime/';
        if(preg_match($match,$url)){
            $message=spider::getTitleByUrl($url,'',$referer);
            if($message){

            }
        }

}
