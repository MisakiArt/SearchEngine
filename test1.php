<?php
require 'vendor/autoload.php';
//$redis=new Redis();
//$redis->connect('127.0.0.1',6379);
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
$resp = $client->indices()->index($params);
var_dump($resp);
?>