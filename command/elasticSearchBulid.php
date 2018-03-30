<?php
require '../vendor/autoload.php';

$hosts = array('127.0.0.1:9200');
$client = Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
$params = [
    'index'=>'search_engine_ani',
    "body"=>[
        "settings"=>["number_of_shards"=>5,"number_of_replicas"=>0],
        'type'=>'all_web_site_record',
        "properties"=>array(
            "id"=>array("type"=>"long"),
            "web_url"=>array("type"=>"string","index"=>"not_analyzed"),//用于统计
            "web_title"=>array("type"=>"string","index"=>"analyzed"),//分词检索
        )
    ]
];
try{
    $resp = $client->indices()->create($params);
    $bulk=['index'=>'search_engine_ani','type'=>'all_web_site_record'];
    $bulk['body'][]=['web_url'=>'http://www.dilidili.wang/','web_title'=>'D站'];
    $bulk['body'][]=['web_url'=>'http://dmhy168.aewb.cn/','web_title'=>'动漫花园'];
    $res=$client->bulk($bulk);
    print_r($res);
}catch (Exception $e){
    var_dump("Error: ".$e->getMessage());
}
