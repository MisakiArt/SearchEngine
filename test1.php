<?php
require 'vendor/autoload.php';
require_once "model/phpQuery.php";
require_once "model/spider.php";
//$redis=new Redis();
// $redis->connect('127.0.0.1',6379);
$hosts = array('127.0.0.1:9200');
$client = Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
$params = [
   'index'=>'website',
   'type'=>'blog',
   'id'=>10,
   'body'=>[
       'title'=>'elasticsearch & php',
       'content'=>'balabala...',
       'created_at'=>'2017-08-01 12:02:20'
   ]
];
$resp = $client->index($params);
var_dump($resp);

//$text='http://www.dilidili.wang/anime/201607/';
//$match='/http:\/\/www.dilidili.wang\//';
//if(preg_match($match,$text)){
//    echo 1;
//}
// $referer='http://www.dilidili.wang';
// $url='http://www.dilidili.wang/anime/caonliqmnxzp2/';
// $message=spider::getTitleByUrl($url,'',$referer);




?>